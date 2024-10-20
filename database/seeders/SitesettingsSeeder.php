<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSettings;

class SiteSettingsSeeder extends Seeder
{
    public function run()
    {
        $factory = SiteSettings::factory();

        $settingsGroups = [
            'site_name' => 'The name of the school management system',
            'site_description' => 'A brief description of the system',
            'admin_email' => 'The main administrative email address',
            'timezone' => 'The default timezone for the system',
            'date_format' => 'The default date format',
            'time_format' => 'The default time format',
            'theme' => 'The current theme of the application',
            'logo_path' => 'Path to the site logo image',
            'favicon_path' => 'Path to the site favicon',
            'primary_color' => 'The primary color used in the UI',
            'secondary_color' => 'The secondary color used in the UI',
            'enable_email_notifications' => 'Whether email notifications are enabled',
            'enable_sms_notifications' => 'Whether SMS notifications are enabled',
            'notification_sender_name' => 'The name that appears as the sender for notifications',
            'attendance_cutoff_time' => 'The time after which students are marked as late',
            'late_threshold_minutes' => 'Minutes after cutoff time before student is marked as late',
            'absent_threshold_minutes' => 'Minutes after cutoff time before student is marked as absent',
            'password_min_length' => 'Minimum required length for passwords',
            'password_require_uppercase' => 'Whether passwords must contain an uppercase letter',
            'password_require_number' => 'Whether passwords must contain a number',
            'password_require_symbol' => 'Whether passwords must contain a symbol',
            'login_attempts_before_lockout' => 'Number of failed login attempts before account is locked',
            'default_language' => 'The default language for the system',
            'available_languages' => 'Comma-separated list of available languages',
            'enable_language_switcher' => 'Whether users can switch languages',
            'facebook_url' => 'URL to the school\'s Facebook page',
            'twitter_url' => 'URL to the school\'s Twitter page',
            'instagram_url' => 'URL to the school\'s Instagram page',
            'linkedin_url' => 'URL to the school\'s LinkedIn page',
            'maintenance_mode' => 'Whether the system is in maintenance mode',
            'system_version' => 'Current version of the system',
            'last_update_check' => 'Date of the last system update check',
            'enable_debug_mode' => 'Whether debug mode is enabled',

        ];

        foreach ($settingsGroups as $key => $value) {
            $factory->create([
                'key' => $key,
                'value' => $value,
                'description' => $value,
                'group' => 'General Settings',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
