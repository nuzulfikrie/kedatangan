<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateAllTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:truncate-all {--exclude=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all tables in the database except the excluded tables';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Get all tables
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $tableKey = "Tables_in_" . $dbName;

        // Get excluded tables from option
        $excludedTables = $this->option('exclude');
        if (!is_array($excludedTables)) {
            $excludedTables = [$excludedTables];
        }

        // Filter out excluded tables
        $tables = array_filter($tables, function ($table) use ($excludedTables, $tableKey) {
            return !in_array($table->$tableKey, $excludedTables);
        });

        // Create progress bar
        $progress = $this->output->createProgressBar(count($tables));
        $progress->start();

        // Truncate each table
        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            $this->info("\nTruncating table: {$tableName}");
            DB::table($tableName)->truncate();
            $this->info("\nTable {$tableName} truncated successfully!");
            $this->newLine();
            $progress->advance();
        }

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $progress->finish();

        $this->newLine();
        $this->info("\nAll tables have been truncated successfully!");
    }
}