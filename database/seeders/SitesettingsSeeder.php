<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sitesettings;

class SiteSettingsSeeder extends Seeder
{
    public function run()
    {
        $factory = Sitesettings::factory();

        foreach ($factory->settingsGroups as $group => $settings) {
            foreach ($settings as $key => $value) {
                $factory->preset($group, $key)->create();
            }
        }

        // You can add or override specific settings here if needed
        Sitesettings::factory()->preset('system', 'maintenance_mode')->create(['value' => 'false']);
        Sitesettings::factory()->preset('system', 'system_version')->create(['value' => '1.0.0']);
    }
}
