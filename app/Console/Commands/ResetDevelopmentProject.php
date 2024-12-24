<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * This command resets the development project by dropping all tables except migrations table,
 * then reruns the migrations for both local and testing environments. 
 * Guide to run this command: php artisan db:reset-development-project --env=local
 */
class ResetDevelopmentProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-development-project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset development project';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Use Laravel's environment detection
        $env = app()->environment();

        if ($env !== 'local') {
            $this->error('❌ This command only works in the development environment.');
            return self::FAILURE;
        }

        try {
            DB::beginTransaction();

            // Drop tables for default connection
            $this->info('Dropping tables for default connection...');
            $this->call('db:drop-tables', [
                '--database' => 'mysql',
                '--force' => true
            ]);

            // Drop tables for testing connection
            $this->info('Dropping tables for testing connection...');
            $this->call('db:drop-tables', [
                '--database' => 'testing',
                '--force' => true
            ]);

            // Run migrations in correct order
            $this->info('Running migrations for default connection...');
            $this->call('migrate:fresh', [
                '--force' => true,
                '--database' => 'mysql'
            ]);

            // Run migrations for testing
            $this->info('Running migrations for testing connection...');
            $this->call('migrate:fresh', [
                '--database' => 'testing',
                '--force' => true
            ]);

            DB::commit();

            $this->info('✅ Development project has been reset successfully.');
            return self::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ An error occurred: ' . $e->getMessage());

            if ($this->option('verbose')) {
                $this->error('Stack trace:');
                $this->error($e->getTraceAsString());
            }

            return self::FAILURE;
        }
    }
}