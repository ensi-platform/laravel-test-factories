<?php

namespace Ensi\LaravelTestFactories;

use Illuminate\Support\ServiceProvider;

class LaravelTestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-test-factories.php', 'laravel-test-factories');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/laravel-test-factories.php' => config_path('laravel-test-factories.php')]);
        }
    }
}
