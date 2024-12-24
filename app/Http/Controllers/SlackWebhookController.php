<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationSetting;
use App\Models\NotificationLog;
use App\Services\OmniMessages\SlackMessageSender;

class SlackWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Verify Slack request signature
        if (!$this->verifySlackRequest($request)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Handle URL verification
        if ($payload['type'] === 'url_verification') {
            return response()->json(['challenge' => $payload['challenge']]);
        }

        // Log webhook event
        NotificationLog::create([
            'notification_setting_id' => $this->findSettingId($payload),
            'event_type' => 'slack_webhook',
            'payload' => $payload,
            'status' => 'received'
        ]);

        return response()->json(['status' => 'ok']);
    }

    public function handleOAuth(Request $request)
    {
        try {
            $code = $request->input('code');
            $state = $request->input('state');

            if (!$code || !$state) {
                throw new \Exception('Missing required OAuth parameters');
            }

            $sender = new SlackMessageSender(config('services.slack.bot_token'));
            $sender->completeBotSetup($code, $state);

            return redirect()->route('notification.settings')
                ->with('success', 'Slack integration completed successfully!');
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('notification.settings')
                ->with('error', 'Failed to complete Slack integration: ' . $e->getMessage());
        }
    }

    protected function verifySlackRequest(Request $request): bool
    {
        $timestamp = $request->header('X-Slack-Request-Timestamp');
        $signature = $request->header('X-Slack-Signature');

        if (abs(time() - $timestamp) > 300) {
            return false;
        }

        $basestring = "v0:{$timestamp}:" . $request->getContent();
        $expectedSignature = 'v0=' . hash_hmac('sha256', $basestring, config('services.slack.signing_secret'));

        return hash_equals($expectedSignature, $signature);
    }

    protected function findSettingId(array $payload): ?int
    {
        $teamId = $payload['team_id'] ?? null;
        if (!$teamId) return null;

        $setting = NotificationSetting::where('service_name', 'slack')
            ->whereJsonContains('settings->team_id', $teamId)
            ->first();

        return $setting?->id;
    }
}
