<?php

namespace App\Providers;

use Faker\Factory;
use Illuminate\Support\ServiceProvider;

class FakerServiceProvider extends ServiceProvider
{
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->app->singleton(Faker\Generator::class, function () {
      return Factory::create();
    });
  }
}
