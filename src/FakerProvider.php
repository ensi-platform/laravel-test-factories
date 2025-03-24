<?php

namespace Ensi\LaravelTestFactories;

use Carbon\CarbonInterface;
use DateTime;
use Faker\Provider\Base;
use Illuminate\Support\Facades\Date;

class FakerProvider extends Base
{
    // null - unchanged
    // false - there will always be a default value
    // true - there will always be a generated value
    public static ?bool $optionalAlways = null;
    public static array $optionalDataset = [null, true, false];

    /**
     * @param null|bool $always
     * @param mixed $default
     *
     * @return static
     */
    public function nullable(?bool $always = null, mixed $default = null)
    {
        $weight = 0.5;
        if (!is_null($always)) {
            $weight = $always ? 100 : 0;
        } elseif (!is_null(static::$optionalAlways)) {
            $weight = static::$optionalAlways ? 100 : 0;
        }

        return parent::optional($weight, $default);
    }

    /**
     * @param null|bool $always
     *
     * @return static
     */
    public function missing(?bool $always = null)
    {
        return static::nullable($always, new FactoryMissingValue());
    }

    /**
     * Generate an array of values. Compatible with $optional Always
     *
     * @param callable $f a callback that returns values for an array
     * @param int $min minimum number in the array
     * @param int $max maximum number in the array
     *
     * @return array
     */
    public function randomList(callable $f, int $min = 0, int $max = 10): array
    {
        $result = [];
        if (!is_null(static::$optionalAlways)) {
            if (static::$optionalAlways) {
                $min = max(1, $min);
            } elseif (!$min) {
                return [];
            }
        }
        $count = $this->generator->numberBetween($min, $max);
        for ($i = 0; $i < $count; $i++) {
            $result[] = $f();
        }

        return $result;
    }

    /**
     * Return a specific value as a fake
     *
     * @param $value
     *
     * @return mixed
     */
    public function exactly($value): mixed
    {
        return $value;
    }

    /**
     * Generate a CarbonInterface date
     * @param DateTime|int|string $max
     * @param string|null $timezone
     * @return CarbonInterface
     */
    public function carbon(DateTime|int|string $max = 'now', ?string $timezone = null): CarbonInterface
    {
        return Date::make($this->generator->dateTime($max, $timezone));
    }

    /**
     * Generate a date that will be longer than the specified one
     *
     * @param null|DateTime $dateFrom Minimum date. If omitted, any date will be generated.
     * @param null|string $format The date format for the response. If the format is not specified, the object will be returned.
     * @param null|DateTime $dateEnd The maximum date. It only works if $dateFrom is passed.
     *
     * @return DateTime|string
     */
    public function dateMore(?DateTime $dateFrom = null, ?string $format = null, ?DateTime $dateEnd = null): DateTime|string
    {
        if ($dateFrom) {
            $date = $this->generator->dateTimeBetween($dateFrom);
        } else {
            $date = $this->generator->dateTime();
        }

        if ($format) {
            return $date->format($format);
        } else {
            return $date;
        }
    }

    /**
     * Generate an Entity ID
     */
    public function modelId(): int
    {
        return $this->generator->numberBetween(1);
    }

    /**
     * Get the value of a random value
     */
    public function randomEnum(array $cases): mixed
    {
        return $this->generator->randomElement($cases)->value;
    }
}
