<?php

namespace Ensi\LaravelTestFactories;

use Ensi\TestFactories\FakerProvider;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;

abstract class BaseModelFactory extends Factory
{
    protected function withFaker(): Generator
    {
        $faker = parent::withFaker();
        $faker->addProvider(new FakerProvider($this->faker));

        return $faker;
    }
}
