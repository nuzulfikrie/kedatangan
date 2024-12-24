<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * This command drops all tables in the database except for the migrations table.
 * # Basic usage 
 *    php artisan db:reset-development-project --env=local 
 *   php artisan db:drop-tables --database=testing --force
 */
class DropTables extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:drop-tables 
        {--database=mysql : The database connection to use}
        {--force : Force the operation to run without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Drop all tables in the database except for the migrations table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $database = $this->option('database');
        $force = $this->option('force');

        // Use Laravel's environment detection
        $env = app()->environment();

        // Production safety check
        if ($env === 'production') {
            $this->error('❌ This command cannot be run in the production environment.');
            return self::FAILURE;
        }

        // Confirmation unless --force is used
        if (!$force && !$this->confirm('⚠️  Are you sure you want to drop all tables except migrations? This cannot be undone!', false)) {
            $this->info('Operation cancelled.');
            return self::SUCCESS;
        }

        try {
            $this->info('🔍 Analyzing database structure...');

            // Get database name and handle hyphens in database names
            $databaseName = DB::connection($database)->getDatabaseName();

            // Get all tables
            $tables = DB::select('SHOW TABLES');
            $tableKey = "Tables_in_" . str_replace('-', '_', $databaseName);

            // Extract table names and filter out migrations, using error suppression for property access
            $tables = array_filter(
                array_map(function ($table) use ($tableKey, $databaseName) {
                    // Handle both hyphenated and non-hyphenated database names
                    $key = "Tables_in_" . str_replace('-', '_', $databaseName);
                    return $table->$key ?? $table->{$tableKey} ?? null;
                }, $tables),
                function ($table) {
                    return $table !== null && $table !== 'migrations';
                }
            );

            if (empty($tables)) {
                $this->info('ℹ️ No tables to drop (excluding migrations table).');
                return self::SUCCESS;
            }

            $this->info(sprintf('📊 Found %d tables to drop.', count($tables)));

            // Create progress bar
            $progressBar = $this->output->createProgressBar(count($tables));
            $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %message%');

            // Drop tables
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $progressBar->start();
            foreach ($tables as $table) {
                $progressBar->setMessage("Dropping $table");
                Schema::dropIfExists($table);
                $this->newLine();
                $this->info("✓ Dropped table: $table");
                $progressBar->advance();
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $progressBar->finish();

            $this->newLine(2);
            $this->info('✅ All tables have been dropped successfully (except migrations).');

            // Output summary
            $this->table(
                ['Status', 'Details'],
                [
                    ['Database', $databaseName],
                    ['Tables Dropped', count($tables)],
                    ['Environment', $env],
                    ['Connection', $database],
                ]
            );

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ An error occurred: ' . $e->getMessage());

            if ($this->option('verbose')) {
                $this->error('Stack trace:');
                $this->error($e->getTraceAsString());
            }

            return self::FAILURE;
        } finally {
            // Ensure foreign key checks are re-enabled
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}