<?php

namespace App\Services\OmniMessages;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\MessageException;
use Illuminate\Support\Facades\DB;

class TelegramMessageSender extends AbstractMessageSender
{
    protected const API_BASE_URL = 'https://api.telegram.org/bot';
    protected const MAX_MESSAGE_LENGTH = 4096;
    protected const MAX_FILE_SIZE = 50 * 1024 * 1024; // 50MB
    protected const ALLOWED_PARSE_MODES = ['HTML', 'MarkdownV2', 'Markdown'];

    protected function getServiceName(): string
    {
        return 'telegram';
    }

    public function validateCredentials(): bool
    {
        return $this->withRetry(function () {
            try {
                $response = Http::get($this->getApiUrl('getMe'));

                if ($response->successful() && ($response->json('ok') ?? false)) {
                    $botInfo = $response->json('result');
                    $this->cacheSet('bot_info', $botInfo);
                    return true;
                }

                return false;
            } catch (\Exception $e) {
                $this->logMessage('Credential validation failed', [
                    'error' => $e->getMessage()
                ], 'error');
                return false;
            }
        });
    }

    public function sendMessage(string $message, string $recipient): bool|string
    {
        return $this->withRetry(function () use ($message, $recipient) {
            $this->validateApiKey();

            try {
                $chatId = $this->resolveChatId($recipient);

                $response = Http::post($this->getApiUrl('sendMessage'), [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                    'protect_content' => true
                ]);

                if ($response->successful() && ($response->json('ok') ?? false)) {
                    $messageId = $response->json('result.message_id');
                    $this->logMessage('Message sent successfully', [
                        'message_id' => $messageId,
                        'chat_id' => $chatId
                    ]);
                    return true;
                }

                $error = $response->json('description') ?? 'Unknown error';
                throw new MessageException("Failed to send Telegram message: {$error}");
            } catch (\Exception $e) {
                $this->logMessage('Failed to send message', [
                    'recipient' => $recipient,
                    'error' => $e->getMessage()
                ], 'error');
                return $e->getMessage();
            }
        });
    }

    public function sendLongMessage(string $message, string $recipient): bool|string
    {
        if (strlen($message) <= self::MAX_MESSAGE_LENGTH) {
            return $this->sendMessage($message, $recipient);
        }

        $chunks = str_split($message, self::MAX_MESSAGE_LENGTH);
        $chatId = $this->resolveChatId($recipient);
        $replyToMessageId = null;

        foreach ($chunks as $index => $chunk) {
            try {
                $response = Http::post($this->getApiUrl('sendMessage'), [
                    'chat_id' => $chatId,
                    'text' => $chunk,
                    'parse_mode' => 'HTML',
                    'reply_to_message_id' => $replyToMessageId,
                    'disable_web_page_preview' => true,
                    'protect_content' => true
                ]);

                if (!$response->successful() || !($response->json('ok') ?? false)) {
                    throw new MessageException("Failed to send message chunk {$index}");
                }

                if ($index === 0) {
                    $replyToMessageId = $response->json('result.message_id');
                }

                // Add small delay to maintain message order
                if ($index < count($chunks) - 1) {
                    usleep(500000); // 0.5 second delay
                }
            } catch (\Exception $e) {
                $this->logMessage('Failed to send long message chunk', [
                    'chunk_index' => $index,
                    'chat_id' => $chatId,
                    'error' => $e->getMessage()
                ], 'error');
                return $e->getMessage();
            }
        }

        return true;
    }

    public function getSendStatus(string $messageId): string
    {
        try {
            $messageInfo = $this->getMessageInfo($messageId);

            if (!$messageInfo) {
                return 'unknown';
            }

            // Check message status based on Telegram message flags
            if ($messageInfo['date'] ?? null) {
                return isset($messageInfo['edit_date']) ? 'edited' : 'delivered';
            }

            return 'unknown';
        } catch (\Exception $e) {
            $this->logMessage('Failed to get message status', [
                'message_id' => $messageId,
                'error' => $e->getMessage()
            ], 'error');
            return 'failed';
        }
    }

    public function deleteMessage(string $messageId): bool|string
    {
        return $this->withRetry(function () use ($messageId) {
            try {
                $messageInfo = $this->getMessageInfo($messageId);
                if (!$messageInfo) {
                    throw new MessageException('Message not found');
                }

                $response = Http::post($this->getApiUrl('deleteMessage'), [
                    'chat_id' => $messageInfo['chat']['id'],
                    'message_id' => $messageId
                ]);

                if ($response->successful() && ($response->json('ok') ?? false)) {
                    $this->logMessage('Message deleted successfully', [
                        'message_id' => $messageId
                    ]);
                    return true;
                }

                $error = $response->json('description') ?? 'Unknown error';
                throw new MessageException("Failed to delete message: {$error}");
            } catch (\Exception $e) {
                $this->logMessage('Failed to delete message', [
                    'message_id' => $messageId,
                    'error' => $e->getMessage()
                ], 'error');
                return $e->getMessage();
            }
        });
    }

    public function getChannelHealth(): array
    {
        try {
            $startTime = microtime(true);
            $response = Http::get($this->getApiUrl('getMe'));
            $latency = (microtime(true) - $startTime) * 1000;

            if ($response->successful() && ($response->json('ok') ?? false)) {
                return [
                    'status' => 'healthy',
                    'latency' => round($latency, 2),
                    'bot_info' => $this->cacheGet('bot_info'),
                    'rate_limits' => [
                        'remaining' => $response->header('X-RateLimit-Remaining'),
                        'reset' => $response->header('X-RateLimit-Reset')
                    ],
                    'timestamp' => now()->toIso8601String()
                ];
            }

            return [
                'status' => 'degraded',
                'error' => $response->json('description') ?? 'Unknown error',
                'timestamp' => now()->toIso8601String()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'timestamp' => now()->toIso8601String()
            ];
        }
    }

    protected function getApiUrl(string $method): string
    {
        return self::API_BASE_URL . $this->apiKey . '/' . $method;
    }

    protected function resolveChatId(string $recipient): string
    {
        // First check cache
        $cachedChatId = $this->cacheGet("chat_id:{$recipient}");
        if ($cachedChatId) {
            return $cachedChatId;
        }

        try {
            // Try to find chat ID from updates
            $response = Http::get($this->getApiUrl('getUpdates'), [
                'limit' => 100,
                'allowed_updates' => ['message', 'channel_post']
            ]);

            if ($response->successful() && ($response->json('ok') ?? false)) {
                $updates = $response->json('result', []);
                foreach ($updates as $update) {
                    $message = $update['message'] ?? $update['channel_post'] ?? null;
                    if (!$message) continue;

                    $username = $message['from']['username'] ?? '';
                    $phoneNumber = $message['contact']['phone_number'] ?? '';

                    if ($username === $recipient || $phoneNumber === $recipient) {
                        $chatId = $message['chat']['id'];
                        $this->cacheSet("chat_id:{$recipient}", $chatId);
                        return $chatId;
                    }
                }
            }

            throw new MessageException("Could not resolve chat ID for recipient: {$recipient}");
        } catch (\Exception $e) {
            $this->logMessage('Failed to resolve chat ID', [
                'recipient' => $recipient,
                'error' => $e->getMessage()
            ], 'error');
            throw $e;
        }
    }

    protected function getMessageInfo(string $messageId): ?array
    {
        $cacheKey = "message:{$messageId}";
        $cachedInfo = $this->cacheGet($cacheKey);

        if ($cachedInfo) {
            return $cachedInfo;
        }

        try {
            // Note: Telegram doesn't have a direct "getMessage" endpoint
            // We'll need to use getChatHistory or similar methods depending on your needs
            $response = Http::get($this->getApiUrl('getUpdates'), [
                'limit' => 100,
                'allowed_updates' => ['message', 'edited_message']
            ]);

            if ($response->successful() && ($response->json('ok') ?? false)) {
                $updates = $response->json('result', []);
                foreach ($updates as $update) {
                    $message = $update['message'] ?? $update['edited_message'] ?? null;
                    if ($message && $message['message_id'] == $messageId) {
                        $this->cacheSet($cacheKey, $message);
                        return $message;
                    }
                }
            }

            return null;
        } catch (\Exception $e) {
            $this->logMessage('Failed to get message info', [
                'message_id' => $messageId,
                'error' => $e->getMessage()
            ], 'error');
            return null;
        }
    }

    /**
     * Initialize bot chat for a user
     * @param int $userId
     * @param string $startCode Unique code user will send to bot
     * @return array
     */
    public function initializeBotChat(int $userId, string $startCode): array
    {
        $this->validateApiKey();

        try {
            // Generate deep linking parameter
            $startParameter = base64_encode("{$userId}_{$startCode}");

            // Get bot information
            $botInfo = $this->getBotInfo();
            $botUsername = $botInfo['username'] ?? null;

            if (!$botUsername) {
                throw new MessageException('Could not retrieve bot username');
            }

            // Store pending setup in settings table
            DB::table('notification_settings')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'service_name' => $this->getServiceName()
                ],
                [
                    'settings' => json_encode([
                        'setup_status' => 'pending',
                        'start_code' => $startCode,
                        'start_parameter' => $startParameter,
                        'created_at' => now()
                    ]),
                    'updated_at' => now()
                ]
            );

            return [
                'setup_url' => "https://t.me/{$botUsername}?start={$startParameter}",
                'bot_username' => $botUsername,
                'expires_at' => now()->addHours(24)
            ];
        } catch (\Exception $e) {
            $this->logMessage('Failed to initialize bot chat', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ], 'error');
            throw $e;
        }
    }

    /**
     * Handle bot start command and complete setup
     * @param string $startParameter
     * @param string $chatId
     * @return bool
     */
    public function completeBotSetup(string $startParameter, string $chatId): bool
    {
        try {
            $decoded = base64_decode($startParameter);
            [$userId, $startCode] = explode('_', $decoded);

            $settings = DB::table('notification_settings')
                ->where('user_id', $userId)
                ->where('service_name', $this->getServiceName())
                ->first();

            if (
                !$settings ||
                !isset($settings->settings) ||
                json_decode($settings->settings)->start_code !== $startCode
            ) {
                throw new MessageException('Invalid setup attempt');
            }

            // Update settings with chat ID
            DB::table('notification_settings')->where('id', $settings->id)->update([
                'settings' => json_encode([
                    'setup_status' => 'completed',
                    'chat_id' => $chatId,
                    'completed_at' => now()
                ]),
                'updated_at' => now()
            ]);

            // Send welcome message
            $this->sendMessage(
                "✅ Setup completed successfully! You will now receive notifications in this chat.",
                $chatId
            );

            return true;
        } catch (\Exception $e) {
            $this->logMessage('Failed to complete bot setup', [
                'parameter' => $startParameter,
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ], 'error');
            throw $e;
        }
    }

    /**
     * Get bot information
     * @return array
     */
    protected function getBotInfo(): array
    {
        $response = Http::get($this->getApiUrl('getMe'));

        if (!$response->successful() || !($response->json('ok') ?? false)) {
            throw new MessageException('Failed to get bot information');
        }

        return $response->json('result');
    }
}
