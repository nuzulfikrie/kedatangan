<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // call seeder for site settings
            $this->call(SiteSettingsSeeder::class);
            $this->command->info('SiteSettingsSeeder completed successfully.');

            //call seeder for schools and institutions
            $this->call(SchoolsInstitutions::class);
            $this->command->info('SchoolsInstitutions seeder completed successfully.');

            //call seeder for users
            $this->call(UserSeeder::class);
            $this->command->info('User seeder completed successfully.');

            DB::commit();
            $this->command->info('All seeders completed successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            $this->command->error('An error occurred during seeding. Rolling back changes.');
            $this->command->error('Error message: ' . $e->getMessage());
            $this->command->error('Error trace: ' . $e->getTraceAsString());

            // Re-throw the exception to stop the seeding process
            throw $e;
        }
    }
}
