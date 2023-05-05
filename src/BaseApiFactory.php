<?php

namespace Ensi\LaravelTestFactories;

use Ensi\TestFactories\Factory;
use Ensi\TestFactories\FactoryMissingValue;

abstract class BaseApiFactory extends Factory
{
    protected function notNull(mixed $value, mixed $default = null): mixed
    {
        $default = func_num_args() === 2 ? $default : new FactoryMissingValue();

        return $this->whenNotNull($value, $value, $default);
    }

    /**
     * @template T
     * @param class-string<T> $classResponse
     * @param array $extras
     * @param int $count
     * @param mixed $pagination
     * @param callable|null $beforeCallback
     * @return T
     */
    protected function generateResponseSearch(
        string $classResponse,
        array $extras = [],
        int $count = 1,
        mixed $pagination = null,
        ?callable $beforeCallback = null,
    ) {
        $meta = $classResponse::openAPITypes()['meta'];

        $data = [];
        $count = $extras ? count($extras) : $count;
        for ($i = 0; $i < $count; $i++) {
            $this->beforeMake($i, $beforeCallback);

            $extra = $extras[$i] ?? [];
            $data[] = is_object($extra) ? $extra : $this->make($extra);
        }

        return new $classResponse([
            'data' => $data,
            'meta' => new $meta([
                'pagination' => $pagination ?: PaginationFactory::new()->makeResponseOffset($meta::openAPITypes()['pagination']),
            ]),
        ]);
    }

    protected function beforeMake(int $index, ?callable $callback = null): void
    {
        if ($callback) {
            $callback($this, $index);
        }
    }
}
