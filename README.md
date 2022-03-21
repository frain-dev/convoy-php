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

$convoy = new Convoy(["api_key" => "your_api_key"]);
```

The SDK also supports authenticating via Basic Auth by defining your username and password.

```php
$convoy = new Convoy(["username" => "default", "password" => "default"]);
```

In the event you're using a self hosted convoy instance, you can define the url as part of what is passed into convoy's constructor.

```php
$convoy = new Convoy([
    "api_key" => "your_api_key",
    "uri" => "self-hosted-instance"
]);
```

#### Creating an Application

An application represents a user's application trying to receive webhooks. Once you create an application, you'll receive an `app_id` that you should save and supply in subsequent API calls to perform other requests such as creating an event.

```php
$appData = ["name" => "my_app", "support_email" => "support@myapp.com"];

$response = $convoy->applications()->create($appData);

$appId = $response['data']['uid'];
```

### Add Application Endpoint

After creating an application, you'll need to add an endpoint to the application you just created. An endpoint represents a target URL to receive events.

```php
$endpointData = [
    "url" => "https://0d87-102-89-2-172.ngrok.io",
    "description" => "Default Endpoint",
    "secret" => "endpoint-secret",
    "events" => ["*"]
]

$response = $convoy->endpoints()->create($appId, $endpointData);
```

### Sending an Event

To send an event, you'll need the `app_id` we created in the earlier section

```php
$eventData = [
    "app_id" => $appId,
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
