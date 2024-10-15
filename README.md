# Laravel Test factories

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ensi/laravel-test-factories.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-test-factories)
[![Tests](https://github.com/ensi-platform/laravel-test-factories/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/ensi-platform/laravel-test-factories/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ensi/laravel-test-factories.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-test-factories)

This package provides factories for typical data structures

## Installation

You can install the package via composer:

```bash
composer require ensi/laravel-test-factories --dev
```

## Version Compatibility

| Laravel Test factories | Laravel                              | PHP  |
|------------------------|--------------------------------------|------|
| ^0.0.1                 | ^8.0                                 | ^8.0 |
| ^0.1.0                 | ^8.0                                 | ^8.0 |
| ^0.2.0                 | ^8.0                                 | ^8.0 |
| ^0.2.4                 | ^8.0 \|\| ^9.0 \|\| ^10.0            | ^8.0 |
| ^0.2.7                 | ^8.0 \|\| ^9.0 \|\| ^10.0 \|\| ^11.0 | ^8.0 |
| ^1.0.0                 | ^9.0 \|\| ^10.0 \|\| ^11.0           | ^8.1 |

## Basic usage

Let's create a factory and extend abstract Factory.
All you need is to define `definition` and `make` methods.

```php
use Ensi\LaravelTestFactories\Factory;

class CustomerFactory extends Factory
{
    public ?int $id = null;
    public ?FileFactory $avatarFactory = null;
    public ?array $addressFactories = null;

    protected function definition(): array
    {
        return [
            'id' => $this->whenNotNull($this->id, $this->id),
            'user_id' => $this->faker->randomNumber(),
            'is_active' => $this->faker->boolean(),
            'date_start' => $this->faker->dateTime(),
            'avatar' => $this->avatarFactory?->make(),
            'addresses' => $this->executeNested($this->addressFactories, new FactoryMissingValue()),
        ];
    }

    public function make(array $extra = []): CustomerDTO
    {
        static::$index += 1;

        return new CustomerDTO($this->mergeDefinitionWithExtra($extra));
    }

    public function withId(?int $id = null): self
    {
        return $this->immutableSet('id', $id ?? $this->faker->randomNumber());
    }

    public function withAvatar(FileFactory $factory = null): self
    {
        return $this->immutableSet('avatarFactory', $factory ?? FileFactory::new());
    }

    public function includesAddresses(?array $factories = null): self
    {
        return $this->immutableSet('addressFactories', $factories ?? [CustomerAddressFactory::new()]);
    }

    public function active(): self
    {
        return $this->state([
            'is_active' => true,
            'date_start' => $this->faker->dateTimeBetween('-30 years', 'now'),
        ]);
    }
}

// Now we can use Factory like that
$customerData1 = CustomerFactory::new()->make();
$customerData2 = CustomerFactory::new()->active()->make();
$customerData3 = CustomerFactory::new()->withId()->withAvatar(FileFactory::new()->someCustomMethod())->make();
```

As you can see the package uses `fakerphp/faker` to generate test data.

You can override any fields in `make` method:

```php
$customerData1 = CustomerFactory::new()->make(['user_id' => 2]);
```

If you target is an array, then you can use a helper method `makeArray`:

```php
    public function make(array $extra = []): array
    {
        return $this->makeArray($extra);
    }
```

It's recommended to use `$this->immutableSet` in state change methods to make sure previously created factories are not affected.

### Making several objects

```php
$customerDataObjects = CustomerFactory::new()->makeSeveral(3); // returns Illuminate\Support\Collection with 3 elements
```

## Additional Faker methods

```php
$this->faker->randomList(fn() => $this->faker->numerify(), 0, 10) // => ['123', ..., '456']
$this->faker->nullable() // equivalent for $this->faker->optional(), but work with boolean parameter or global static setting
$this->faker->exactly($value) // return $value. Example: $this->faker->nullable()->exactly(AnotherFactory::new()->make())
$this->faker->carbon() // return CarbonInterface
$this->faker->dateMore()
$this->faker->modelId() // return unsigned bit integer value
```

## Additional traits

### WithSetPkTrait

If your model has unique index consisting of multiple fields, WithSetPkTrait trait should be used to ensure generated values for these fields are unique.

In order for trait to work, you have to define methods `state`, `setPk`, `sequence` and `generatePk` and include `getPk` call in `definition`.
Following is the example of a factory ('client_id' and 'location_id' are fields forming unique index):

```php
    class ClientAmountFactory extends BaseModelFactory
{
    use WithSetPkTrait;

    protected $model = ClientAmount::class;

    public function definition(): array
    {
        return array_merge($this->getPk(), [
            'amount' => $this->faker->numberBetween(1, 1_000_000),
        ]);
    }

    public function setPk(?int $clientId = null, ?string $locationId = null): self // Use in tests to define values
    {
        return $this->state(function () use ($clientId, $locationId) {
            return $this->generatePk($clientId, $locationId);
        });
    }

    public function state(mixed $state): static // Override of Laravel Eloquent Factory method
    {
        return $this->stateSetPk($state, ['client_id', 'location_id']);
    }

    public function sequence(...$sequence): static // Override of Laravel Eloquent Factory method
    {
        return $this->stateSetPk($sequence, ['client_id', 'location_id'], true);
    }

    protected function generatePk(?int $clientId = null, ?string $locationId = null): array
    {
        $clientIdFormat = $clientId ?: '\d{10}';
        $locationIdFormat = $locationId ?: '[0-9]{1,10}';

        $unique = $this->faker->unique()->regexify("/^{$clientIdFormat}_{$locationIdFormat}");

        $uniqueArr = explode('_', $unique);

        return [
            'client_id' => (int)$uniqueArr[0],
            'location_id' => $uniqueArr[1],
        ];
    }
}
```

*Important note* - fields must be declared in the same order in all methods.

## Parent classes

### BaseApiFactory

This class is the base for your factories of various Api responses/requests.

It also provides the `generateResponseSearch` method, which allows you to generate a response in the `:search` format of the endpoint described [here](https://docs.ensi.tech/guidelines/api#стандартные-методы-search)

### BaseModelFactory

This class is the base class for your Eloquent model factories

## Factories

- `PaginationFactory` - factory for generating response pieces and pagination requests
- `PromiseFactory` - factory for generating `GuzzleHttp\Promise\PromiseInterface` 

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

### Testing

1. composer install
2. composer test

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
