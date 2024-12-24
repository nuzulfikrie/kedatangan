<?php

namespace App\Services\OmniMessages;

use Illuminate\Support\Facades\Http;
use App\Exceptions\MessageException;
use Illuminate\Support\Facades\DB;

class SlackMessageSender extends AbstractMessageSender
{
    protected const API_BASE_URL = 'https://slack.com/api/';
    protected const MAX_MESSAGE_LENGTH = 40000;

    protected function getServiceName(): string
    {
        return 'slack';
    }
    /**
     * Initialize bot setup for a user
     * @param int $userId
     * @return array
     */
    public function initializeBotChat(int $userId): array
    {
        $this->validateApiKey();

        try {
            // Get bot information using auth.test
            $botInfo = $this->getBotInfo();

            // Generate state parameter for OAuth
            $state = base64_encode(json_encode([
                'user_id' => $userId,
                'timestamp' => time()
            ]));

            // Store pending setup in settings table
            DB::table('notification_settings')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'service_name' => $this->getServiceName()
                ],
                [
                    'settings' => json_encode([
                        'setup_status' => 'pending',
                        'state' => $state,
                        'created_at' => now()
                    ]),
                    'updated_at' => now()
                ]
            );

            // Get OAuth URL
            $scopes = ['chat:write', 'im:write'];
            $clientId = config('services.slack.client_id');
            $redirectUri = config('services.slack.redirect_uri');

            return [
                'setup_url' => "https://slack.com/oauth/v2/authorize?" . http_build_query([
                    'client_id' => $clientId,
                    'scope' => implode(',', $scopes),
                    'redirect_uri' => $redirectUri,
                    'state' => $state
                ]),
                'bot_name' => $botInfo['bot_name'] ?? null,
                'expires_at' => now()->addHours(24)
            ];
        } catch (\Exception $e) {
            $this->logMessage('Failed to initialize bot setup', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ], 'error');
            throw $e;
        }
    }

    /**
     * Complete bot setup after OAuth
     * @param string $code
     * @param string $state
     * @return bool
     */
    public function completeBotSetup(string $code, string $state): bool
    {
        try {
            // Decode state parameter
            $stateData = json_decode(base64_decode($state), true);
            $userId = $stateData['user_id'] ?? null;

            if (!$userId) {
                throw new MessageException('Invalid state parameter');
            }

            // Exchange code for access token
            $response = Http::post('https://slack.com/api/oauth.v2.access', [
                'client_id' => config('services.slack.client_id'),
                'client_secret' => config('services.slack.client_secret'),
                'code' => $code,
                'redirect_uri' => config('services.slack.redirect_uri')
            ]);

            if (!$response->successful()) {
                throw new MessageException('Failed to exchange OAuth code');
            }

            $data = $response->json();

            if (!($data['ok'] ?? false)) {
                throw new MessageException($data['error'] ?? 'OAuth exchange failed');
            }

            // Create DM channel
            $channelResponse = Http::withToken($data['access_token'])
                ->post('https://slack.com/api/conversations.open', [
                    'users' => $data['authed_user']['id']
                ]);

            $channelData = $channelResponse->json();
            if (!($channelData['ok'] ?? false)) {
                throw new MessageException('Failed to create DM channel');
            }

            // Update settings
            DB::table('notification_settings')
                ->where('user_id', $userId)
                ->where('service_name', $this->getServiceName())
                ->update([
                    'settings' => json_encode([
                        'setup_status' => 'completed',
                        'access_token' => encrypt($data['access_token']),
                        'channel_id' => $channelData['channel']['id'],
                        'user_id' => $data['authed_user']['id'],
                        'team_id' => $data['team']['id'],
                        'completed_at' => now()
                    ]),
                    'updated_at' => now()
                ]);

            // Send welcome message
            $this->sendMessage(
                "✅ Setup completed successfully! You will now receive notifications in this channel.",
                $channelData['channel']['id']
            );

            return true;
        } catch (\Exception $e) {
            $this->logMessage('Failed to complete bot setup', [
                'state' => $state,
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
        $response = Http::withToken($this->apiKey)
            ->get('https://slack.com/api/auth.test');

        if (!$response->successful() || !($response->json('ok') ?? false)) {
            throw new MessageException('Failed to get bot information');
        }

        return $response->json();
    }
    public function validateCredentials(): bool
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->get(self::API_BASE_URL . 'auth.test');

            $isValid = $response->successful() && ($response->json('ok') ?? false);

            if ($isValid) {
                $this->cacheSet('team_id', $response->json('team_id'));
                $this->cacheSet('bot_id', $response->json('bot_id'));
            }

            return $isValid;
        } catch (\Exception $e) {
            $this->logMessage('Credential validation failed', [
                'error' => $e->getMessage()
            ], 'error');
            return false;
        }
    }

    public function sendMessage(string $message, string $recipient): bool|string
    {
        return $this->withRetry(function () use ($message, $recipient) {
            $this->validateApiKey();

            $response = Http::withToken($this->apiKey)
                ->post(self::API_BASE_URL . 'chat.postMessage', [
                    'channel' => $recipient,
                    'text' => $message,
                    'unfurl_links' => false,
                    'metadata' => [
                        'event_type' => 'omni_message',
                        'event_payload' => [
                            'source' => 'omni_messages',
                            'timestamp' => now()->timestamp
                        ]
                    ]
                ]);

            if ($response->successful() && ($response->json('ok') ?? false)) {
                $messageId = $response->json('ts');
                $this->logMessage('Message sent successfully', [
                    'message_id' => $messageId,
                    'recipient' => $recipient
                ]);
                return true;
            }

            $error = $response->json('error') ?? 'Unknown error';
            throw new MessageException("Failed to send Slack message: {$error}");
        });
    }

    public function sendLongMessage(string $message, string $recipient): bool|string
    {
        if (strlen($message) <= self::MAX_MESSAGE_LENGTH) {
            return $this->sendMessage($message, $recipient);
        }

        $chunks = str_split($message, self::MAX_MESSAGE_LENGTH);
        $threadTs = null;

        foreach ($chunks as $index => $chunk) {
            try {
                $response = Http::withToken($this->apiKey)
                    ->post(self::API_BASE_URL . 'chat.postMessage', [
                        'channel' => $recipient,
                        'text' => $chunk,
                        'thread_ts' => $threadTs,
                        'metadata' => [
                            'event_type' => 'long_message',
                            'part' => $index + 1,
                            'total_parts' => count($chunks)
                        ]
                    ]);

                if (!$response->successful() || !($response->json('ok') ?? false)) {
                    throw new MessageException("Failed to send message chunk {$index}");
                }

                if ($index === 0) {
                    $threadTs = $response->json('ts');
                }
            } catch (\Exception $e) {
                $this->logMessage('Failed to send long message', [
                    'error' => $e->getMessage(),
                    'chunk' => $index,
                    'recipient' => $recipient
                ], 'error');
                return $e->getMessage();
            }
        }

        return true;
    }

    public function getSendStatus(string $messageId): string
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->get(self::API_BASE_URL . 'conversations.history', [
                    'channel' => $this->getChannelFromMessageId($messageId),
                    'latest' => $messageId,
                    'limit' => 1,
                    'inclusive' => true
                ]);

            if ($response->successful() && ($response->json('ok') ?? false)) {
                $messages = $response->json('messages', []);
                if (!empty($messages)) {
                    return 'delivered';
                }
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
                $response = Http::withToken($this->apiKey)
                    ->post(self::API_BASE_URL . 'chat.delete', [
                        'channel' => $this->getChannelFromMessageId($messageId),
                        'ts' => $messageId
                    ]);

                if ($response->successful() && ($response->json('ok') ?? false)) {
                    $this->logMessage('Message deleted successfully', [
                        'message_id' => $messageId
                    ]);
                    return true;
                }

                $error = $response->json('error') ?? 'Unknown error';
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
            $response = Http::withToken($this->apiKey)
                ->get(self::API_BASE_URL . 'api.test');

            return [
                'status' => $response->successful() ? 'healthy' : 'degraded',
                'latency' => $response->transferStats->getTransferTime() * 1000,
                'rate_limited' => $response->header('Retry-After') !== null,
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

    protected function getChannelFromMessageId(string $messageId): string
    {
        // Implementation depends on how you store/retrieve channel IDs
        // You might want to store this in your database or cache
        return $this->config['default_channel'] ?? throw new MessageException('Channel not found for message');
    }
}
