<?php

namespace Ensi\LaravelTestFactories;

use Carbon\CarbonInterface;
use DateTime;

/**
 * @method ChanceGenerator nullable(?bool $always = null, mixed $default = null)
 * @method ChanceGenerator missing(?bool $always = null)
 * @method array randomList(callable $f, int $min = 0, int $max = 10)
 * @method mixed exactly(mixed $value)
 * @method CarbonInterface carbon(DateTime|int|string $max = 'now', ?string $timezone = null)
 * @method DateTime|string dateMore(DateTime|string|null $dateFrom = null, ?string $format = null, DateTime|string|null $dateEnd = 'now')
 * @method int modelId()
 * @method mixed randomEnum(array $cases)
 */
class ChanceGenerator extends \Faker\ChanceGenerator
{
}
