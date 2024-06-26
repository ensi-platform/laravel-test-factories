<?php

namespace Ensi\LaravelTestFactories\Tests;

use Ensi\LaravelTestFactories\LaravelTestServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelTestServiceProvider::class,
        ];
    }
}
