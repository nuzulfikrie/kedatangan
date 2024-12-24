<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabaseSetup extends Command
{
    protected $signature = 'test:db-setup';
    protected $description = 'Set up test database';

    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Create test database if it doesn't exist
        DB::statement(
            'CREATE DATABASE IF NOT EXISTS microservice-test'
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('Test database setup completed');
    }
}
