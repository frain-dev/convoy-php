# Convoy SDK for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/frain/convoy.svg?style=flat-square)](https://packagist.org/packages/frain/convoy)
[![Tests](https://github.com/frain/convoy/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/frain/convoy/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/frain/convoy.svg?style=flat-square)](https://packagist.org/packages/frain/convoy)

This is the Convoy PHP SDK. This SDK contains methods for easily interacting with Convoy's API. Below are examples to get you started. For additional examples, please see our official documentation at (https://convoy.readme.io/reference)


## Installation

You can install the package via composer:

```bash
composer require frain/convoy
```

## Usage

```php
use Convoy\HttpClient\Config;
use Convoy\Convoy;


$config = new Config(['api_key' => 'your_api_key']);

$convoy = new Convoy($config);

//Fetch all groups
$groups = $convoy->groups()->all();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Frain](https://github.com/frain)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
