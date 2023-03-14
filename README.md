# Laravel Test factories

Данный пакет предоставляет фабрики для типичных структур данных

## Установка

Вы можете установить пакет через composer:

`composer require ensi/laravel-test-factories --dev`

## Использование

### Родительские классы

#### BaseApiFactory

Данный класс является базовым для ваших фабрик различных ответов/запросов для Api. 

Помимо прочего предоставляет метод `generateResponseSearch`, который позволяет генерировать ответ в формате `:search` эндпоинта, описанного [тут](https://docs.ensi.tech/guidelines/api#стандартные-методы-search)

#### BaseModelFactory

Данный класс является базовым для ваших фабрик Eloquent моделей

### Готовые фабрики

- `PaginationFactory` - фабрика для генерации кусочков ответов и запросов пагинации
- `PromiseFactory` - фабрика для генерации `GuzzleHttp\Promise\PromiseInterface` 

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
