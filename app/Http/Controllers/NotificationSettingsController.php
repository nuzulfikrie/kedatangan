<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationSetting;
use App\Services\OmniMessages\TelegramMessageSender;
use App\Services\OmniMessages\SlackMessageSender;
use Illuminate\Support\Str;

class NotificationSettingsController extends Controller
{
    public function index()
    {
        $settings = [];
        $userSettings = NotificationSetting::where('user_id', auth()->id)->get();

        foreach ($userSettings as $setting) {
            $settings[$setting->service_name] = $setting->settings;
        }

        return view('notification-settings', [
            'settings' => $settings,
            'setupInProgress' => session('setup_in_progress', false)
        ]);
    }

    public function setup(Request $request, string $service)
    {
        try {
            $sender = match ($service) {
                'telegram' => new TelegramMessageSender(config('services.telegram.bot_token')),
                'slack' => new SlackMessageSender(config('services.slack.bot_token')),
                default => throw new \Exception('Invalid service')
            };

            if ($service === 'telegram') {
                $setup = $sender->initializeBotChat(auth()->id, Str::random(32));
            } else {
                $setup = $sender->initializeBotChat(auth()->id);
            }

            session(['setup_in_progress' => true]);

            if ($request->wantsJson()) {
                return response()->json($setup);
            }

            return redirect($setup['setup_url']);
        } catch (\Exception $e) {
            report($e);

            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return back()->with('error', 'Failed to initialize setup: ' . $e->getMessage());
        }
    }
}
