# SumUp PHP API Client

## Versioning

This library follows [Semantic Versioning](http://semver.org/).

Note that the library is currently in active development and backwards compatibility is not guaranteed.

## How To Use

Facing the developer is a `SumupClient` class. Through this class you can instantiate new services.

The `SumupClient` class accepts two optional arguments: `Configuration` and `Container`. If no arguments are passed, the script uses defaults.

To use SumUp's API via this package, you need to use services. For example, to create a new shelf you would do something like this:

```php
$client = new SumupClient();
$shelfService = $client->createService('shelf');
$data = [
    'name' => 'My new shelf'
];
$shelf = $shelfService->create($data);
```

A service can return three types of values: an entity representing object, a boolean for success, or a collection of entities.

### Services

Currently available services include:
* account
* merchant.profile
* profile
* shelf
* product
* price
* business
* operator
* bank_account
* payout.settings
* app.settings
* checkout
* customer
* transaction
* payment_instrument
* receipt
* refund

### Configuration

There are two types of configuration, via the DotEnv library and the `Configuration` class.

For the DotEnv configuration, please, refer to `.env.example` file. Copy this file or its contents to `.env` to apply any changes.

The `Configuration` class holds information about API connectivity settings, grant types, other internal data. Usually this is filled in automatically, but should you need anything specific, feel free to extend it and inject your own class into `SumupClient`.

### Authentication

This package identifies the merchant through oAuth 2.0. Currently supported grant type is Resource Owner Password Credentials Grant (`password`).

### Container

This package relies on a simple Dependency Injection implementation, supported by [Pimple](https://github.com/silexphp/Pimple). You can override our `SumupContainer` class with your own. It is recommended to keep the package's current setup via calling `parent::__construct()` in your own classes' constructor.

### Caching

This package uses caching to store temporary oAuth access token. By default the storage engine uses the filesystem (`FileCacheItemPoool` and `FileCacheItem`) for all caching operations.

You can create your own caching solution by following PSR-7 and replacing `cache.pool` in `SumupContainer`.

### Error Handling

When calling SumUp services, if something goes wrong, the package throws a `RequestException`. The exception object contains two methods: `getError()` and `getErrors()`. The first method returns the first occurred error and the second returns an array with errors.

Each error is wrapped in an `ApiError` object. You can use the following methods to get information about the error itself: `getErrorCode()`,  `getParam()`, and `getMessage()`.

### Helper Functions

This package comes with a few helper functions:

* `snakeCaseToCamelCase()` – converts a string in a snake_case format to camelCase.
* `camelCaseToSnakeCase()` – converts a string in a camelCase format to snake_case.
* `dd()` – this function is effectively replacing `var_dump()` and `die()`.
* `isAssociativeArray()` – returns a boolean on whether the passed array is associative or sequential.

### Testing

If you want to run the integration tests, you will need full scope access. Please, contact our support with your request.

You can use the integration tests for examples on how to use this package.

## How To Contribute

Please, see [Contributing](CONTRIBUTING.md).
