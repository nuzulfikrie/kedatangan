<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DBCreate extends Command
{
    protected $signature = 'db:create {name}';
    protected $description = 'Create a new database';

    public function handle()
    {
        $databaseName = $this->argument('name');

        // Ensure $databaseName is a string
        if (!is_string($databaseName)) {
            $this->error('Invalid database name.');
            return 1;
        }

        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}`");
            $this->info("Database `{$databaseName}` created successfully.");
        } catch (\Exception $e) {
            $this->error("Failed to create database `{$databaseName}`: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}