# Laravel Test factories

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ensi/laravel-test-factories.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-test-factories)
[![Tests](https://github.com/ensi-platform/laravel-test-factories/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/ensi-platform/laravel-test-factories/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ensi/laravel-test-factories.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-test-factories)

This package provides factories for typical data structures

## Установка

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
| ^0.3.0                 | ^9.0 \|\| ^10.0 \|\| ^11.0           | ^8.1 |

## Basic usage

### Parent classes

#### BaseApiFactory

This class is the base for your factories of various Api responses/requests.

It also provides the `generateResponseSearch` method, which allows you to generate a response in the `:search` format of the endpoint described [here](https://docs.ensi.tech/guidelines/api#стандартные-методы-search)

#### BaseModelFactory

This class is the base class for your Eloquent model factories

### Factories

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
