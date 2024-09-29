<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // call seeder for site settings
        $this->call(SiteSettingsSeeder::class);
        //call seeder for schools and institutions
        $this->call(SchoolsInstitutions::class);
        //call seeder for users
        $this->call(User::class);
    }
}
