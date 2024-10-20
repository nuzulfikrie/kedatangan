<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Console\Commands\ResetAndSeedDatabase;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton('commands.reset-and-seed', function ($app) {
            return new ResetAndSeedDatabase;
        });

        $this->commands('commands.reset-and-seed');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        if (env('APP_DEBUG') === true) {
            DB::listen(function ($query) {
                // Convert DateTime objects in bindings to strings
                $bindings = array_map(function ($binding) {
                    return $binding instanceof \DateTime ? $binding->format('Y-m-d H:i:s') : $binding;
                }, $query->bindings);

                Log::info(
                    "SQL Query: " . $query->sql .
                        " [Bindings: " . implode(", ", $bindings) . "] " .
                        " [Time: " . $query->time . "ms]"
                );
            });
        }
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email . $request->ip());
        });
    }
}
