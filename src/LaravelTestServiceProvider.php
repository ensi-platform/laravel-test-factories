<?php

namespace Ensi\LaravelTestFactories;

use Illuminate\Support\ServiceProvider;

class LaravelTestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-test-factories.php', 'laravel-test-factories');
    }

    public function boot()
    {
//        if ($this->app->runningInConsole()) {
//
//        }
    }
}
