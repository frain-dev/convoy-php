# Convoy SDK for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/frain/convoy.svg?style=flat-square)](https://packagist.org/packages/frain/convoy)
[![Total Downloads](https://img.shields.io/packagist/dt/frain/convoy.svg?style=flat-square)](https://packagist.org/packages/frain/convoy)

This is the Convoy PHP SDK. This SDK contains methods for easily interacting with Convoy's API. Below are examples to get you started. For additional examples, please see our official documentation at (https://convoy.readme.io/reference)


## Installation
To install the package, you will need to be using Composer in your project. 

The Convoy PHP SDK is not hard coupled to any HTTP Client such as Guzzle or any other library used to make HTTP requests. The HTTP Client implementation is based on PSR-18(https://www.php-fig.org/psr/psr-18/). This provides you with the convenience of choosing what PSR-7(https://packagist.org/providers/psr/http-message-implementation) and HTTP Client(https://packagist.org/providers/psr/http-client-implementation) you want to use.

To get started quickly, 

```bash
composer require frain/convoy symfony/http-client nyholm/psr7
```

## Usage

```php
use Convoy\HttpClient\Config;
use Convoy\Convoy;


$config = new Config([
    'api_key' => 'your_api_key',
    'uri' => 'https://self-hosted-convoy' //This is optional and will default to https://cloud.getconvoy.io/api/v1
]);

$convoy = new Convoy($config);

//Group Resource
$groups = $convoy->groups()->all();
$group = $convoy->groups()->find('group-uuid')
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
