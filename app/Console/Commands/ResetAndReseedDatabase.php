<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;

class ResetAndSeedDatabase extends Command
{
    protected $signature = 'db:reset-and-seed';

    protected $description = 'Truncate all tables except system tables and re-seed the database';

    protected $excludedTables = [
        'jobs',
        'failed_jobs',
        'migrations',
        'password_reset_tokens',
        'personal_access_tokens',
        'telescope_entries',
        'telescope_entries_tags',
        'telescope_monitoring',
    ];

    public function handle()
    {
        if (App::environment('production')) {
            $this->error('This command cannot be run in production environment.');
            return 1;
        }

        if (!$this->confirm('This will delete all data in the database. Are you sure you want to continue?')) {
            $this->info('Command cancelled.');
            return;
        }

        $this->info('Truncating tables...');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tables as $table) {
            if (!in_array($table, $this->excludedTables)) {
                $this->info("Truncating table: {$table}");
                DB::table($table)->truncate();
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('All tables truncated successfully.');

        $this->info('Running DatabaseSeeder...');
        $this->call('db:seed');

        $this->info('Database reset and seed completed successfully.');
    }
}
