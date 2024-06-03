<?php

namespace App\Console\Commands\Reload;

use Illuminate\Console\Command;

class CacheCommand extends Command
{
    protected $signature = 'reload:cache';

    protected $description = 'Reload all caches';

    public function handle(): void
    {
        $this->info('This will reload all cache ...');

        $tasks = [
            'event:clear',
            'optimize:clear',
            'route:clear',
            'view:clear',
            'config:clear',
            'cache:clear'
        ];

        $progressBar = $this->output->createProgressBar(count($tasks));

        $progressBar->start();

        foreach ($tasks as $task) {
            $this->call($task);
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->info("\nSuccessfully reloaded caches.");
    }
}
