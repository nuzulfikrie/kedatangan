<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationSetting;
use App\Models\NotificationLog;
use App\Services\OmniMessages\TelegramMessageSender;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Handle /start command
        if (isset($payload['message']['text']) && str_starts_with($payload['message']['text'], '/start')) {
            return $this->handleStartCommand($payload);
        }

        // Log webhook event
        NotificationLog::create([
            'notification_setting_id' => $this->findSettingId($payload),
            'event_type' => 'telegram_webhook',
            'payload' => $payload,
            'status' => 'received'
        ]);

        return response()->json(['status' => 'ok']);
    }

    protected function handleStartCommand(array $payload): \Illuminate\Http\JsonResponse
    {
        try {
            $startParameter = substr($payload['message']['text'], 7); // Remove "/start "
            $chatId = $payload['message']['chat']['id'];

            if (empty($startParameter)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid start parameter']);
            }

            $sender = new TelegramMessageSender(config('services.telegram.bot_token'));
            $sender->completeBotSetup($startParameter, (string)$chatId);

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    protected function findSettingId(array $payload): ?int
    {
        $chatId = $payload['message']['chat']['id'] ?? null;
        if (!$chatId) return null;

        $setting = NotificationSetting::where('service_name', 'telegram')
            ->whereJsonContains('settings->chat_id', (string)$chatId)
            ->first();

        return $setting?->id;
    }
}
