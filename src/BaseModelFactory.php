<?php

namespace Ensi\LaravelTestFactories;

use Ensi\TestFactories\FakerProvider;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @property Generator|FakerProvider $faker
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 * @extends Factory<TModel>
 */
abstract class BaseModelFactory extends Factory
{
    protected function withFaker(): Generator
    {
        $faker = parent::withFaker();
        $faker->addProvider(new FakerProvider($faker));

        return $faker;
    }
}
