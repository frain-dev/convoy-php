# Convoy SDK for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/frain/convoy.svg?style=flat-square)](https://packagist.org/packages/frain/convoy)
[![Total Downloads](https://img.shields.io/packagist/dt/frain/convoy.svg?style=flat-square)](https://packagist.org/packages/frain/convoy)

This is the Convoy PHP SDK. This SDK contains methods for easily interacting with Convoy's API. Below are examples to get you started. For additional examples, please see our official documentation at (https://convoy.readme.io/reference)


## Installation
To install the package, you will need to be using Composer in your project. 

The Convoy PHP SDK is not hard coupled to any HTTP Client such as Guzzle or any other library used to make HTTP requests. The HTTP Client implementation is based on [PSR-18](https://www.php-fig.org/psr/psr-18/). This provides you with the convenience of choosing what [PSR-7](https://packagist.org/providers/psr/http-message-implementation) and [HTTP Client](https://packagist.org/providers/psr/http-client-implementation) you want to use.

To get started quickly, 

```bash
composer require frain/convoy symfony/http-client nyholm/psr7
```

### Setup Client

Next, import the `convoy` module and setup with your auth credentials.

```php
use Convoy\Convoy;

$convoy = new Convoy(["api_key" => "your_api_key", "project_id" => "your_project_id"]);
```

### Create an Endpoint

An endpoint represents a target URL to receive events.

```php
$endpointData = [
    "name" => "Default Endpoint",
    "url" => "https://0d87-102-89-2-172.ngrok.io",
    "description" => "Default Endpoint",
    "secret" => "endpoint-secret",
    "events" => ["*"]
];

$response = $convoy->endpoints()->create($endpointData);
```

### Update an Endpoint

```php
$endpointId = "01GTVFSGBAH8NJTMT5Y1ENE218";

$endpointData = [
    "name" => "Default Endpoint",
    "url" => "https://0d87-102-89-2-172.ngrok.io",
    "description" => "Default Endpoint",
    "secret" => "endpoint-secret",
    "events" => ["*"]
];

$response = $convoy->endpoints()->update($endpointId, $endpointData);
```

### Sending an Event

To send an event, you'll need the `uid` from the endpoint we created earlier.

```php
$eventData = [
    "endpoint_id" => $endpointId,
    "event_type" => "payment.success",
    "data" => [
        "event" => "payment.success",
        "data" => [
            "status" => "Completed",
            "description" => "Transaction Successful",
            "userID" => "test_user_id808"
        ]
    ]
];

$response = $convoy->events()->create($eventData);
```

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Frain](https://github.com/frain-dev)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
