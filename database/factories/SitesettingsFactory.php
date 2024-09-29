<?php

namespace Database\Factories;

use App\Models\Sitesettings;
use Illuminate\Database\Eloquent\Factories\Factory;

class SitesettingsFactory extends Factory
{
    protected $model = Sitesettings::class;

    protected $settingsGroups = [
        'general' => [
            'site_name' => 'School Management System',
            'site_description' => 'Comprehensive school management solution',
            'admin_email' => 'admin@schoolsystem.com',
            'timezone' => 'Asia/Kuala_Lumpur',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i',
        ],
        'appearance' => [
            'theme' => 'default',
            'logo_path' => '/images/logo.png',
            'favicon_path' => '/images/favicon.ico',
            'primary_color' => '#007bff',
            'secondary_color' => '#6c757d',
        ],
        'notifications' => [
            'enable_email_notifications' => 'true',
            'enable_sms_notifications' => 'false',
            'notification_sender_name' => 'School System',
        ],
        'attendance' => [
            'attendance_cutoff_time' => '09:00',
            'late_threshold_minutes' => '15',
            'absent_threshold_minutes' => '60',
        ],
        'security' => [
            'password_min_length' => '8',
            'password_require_uppercase' => 'true',
            'password_require_number' => 'true',
            'password_require_symbol' => 'true',
            'login_attempts_before_lockout' => '5',
        ],
        'localization' => [
            'default_language' => 'en',
            'available_languages' => 'en,ms,zh,ta',
            'enable_language_switcher' => 'true',
        ],
        'social_media' => [
            'facebook_url' => '',
            'twitter_url' => '',
            'instagram_url' => '',
            'linkedin_url' => '',
        ],
        'system' => [
            'maintenance_mode' => 'false',
            'system_version' => '1.0.0',
            'last_update_check' => '',
            'enable_debug_mode' => 'false',
        ],
    ];

    public function definition()
    {
        $group = $this->faker->randomElement(array_keys($this->settingsGroups));
        $key = $this->faker->randomElement(array_keys($this->settingsGroups[$group]));

        return [
            'key' => $key,
            'value' => $this->settingsGroups[$group][$key],
            'description' => $this->getDescriptionForKey($key),
            'group' => $group,
            'active' => $this->faker->boolean(90), // 90% chance of being active
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function preset(string $group, string $key)
    {
        return $this->state(function (array $attributes) use ($group, $key) {
            return [
                'key' => $key,
                'value' => $this->settingsGroups[$group][$key],
                'description' => $this->getDescriptionForKey($key),
                'group' => $group,
                'active' => true,
            ];
        });
    }

    protected function getDescriptionForKey($key)
    {
        $descriptions = [
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

        return $descriptions[$key] ?? 'Configuration setting for ' . str_replace('_', ' ', $key);
    }
}
