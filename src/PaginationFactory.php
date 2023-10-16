<?php

namespace Ensi\LaravelTestFactories;

class PaginationFactory extends BaseApiFactory
{
    protected function definition(): array
    {
        $limit = $this->faker->numberBetween(1, 200);
        $offset = $this->faker->numberBetween(0, 200);

        return [
            'type' => $this->faker->randomElement(config('laravel-test-factories.pagination')),
            'cursor' => $this->faker->bothify('******************************'),
            'next_cursor' => $this->faker->bothify('******************************'),
            'previous_cursor' => $this->faker->bothify('******************************'),
            'limit' => $limit,
            'offset' => $offset,
            'total' => $this->faker->numberBetween($limit + $offset, $limit + $offset + 200),
        ];
    }

    public function make(array $extra = []): array
    {
        return $this->makeArray($extra);
    }

    public function makeResponseOffset(string $className, array $extra = [])
    {
        $arr = $this
            ->only(['type', 'limit', 'total', 'offset'])
            ->make(array_merge($extra, [
                'type' => config('laravel-test-factories.pagination.offset'),
            ]));

        return new $className($arr);
    }

    public function makeRequestOffset(array $extra = []): array
    {
        return $this
            ->only(['type', 'limit', 'offset'])
            ->make(array_merge($extra, [
                'type' => config('laravel-test-factories.pagination.offset'),
            ]));
    }

    public function makeResponseCursor(string $className, array $extra = [])
    {
        $arr = $this
            ->only(['type', 'limit', 'cursor', 'next_cursor', 'previous_cursor'])
            ->make(array_merge($extra, [
                'type' => config('laravel-test-factories.pagination.cursor'),
            ]));

        return new $className($arr);
    }

    public function makeRequestCursor(array $extra = []): array
    {
        return $this
            ->only(['type', 'limit', 'cursor', 'next_cursor', 'previous_cursor'])
            ->make(array_merge($extra, [
                'type' => config('laravel-test-factories.pagination.cursor'),
            ]));
    }
}
