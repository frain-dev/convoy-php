# Convoy SDK for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/frain/convoy.svg?style=flat-square)](https://packagist.org/packages/frain/convoy)
[![Total Downloads](https://img.shields.io/packagist/dt/frain/convoy.svg?style=flat-square)](https://packagist.org/packages/frain/convoy)

This is the Convoy PHP SDK. This SDK contains methods for easily interacting with Convoy's API. Below are examples to get you started. See our [API Reference](https://getconvoy.io/docs/api-reference/welcome) for more.


## Installation
To install the package, you will need to be using Composer in your project. 

The Convoy PHP SDK is not hard coupled to any HTTP Client such as Guzzle or any other library used to make HTTP requests. The HTTP Client implementation is based on [PSR-18](https://www.php-fig.org/psr/psr-18/). This provides you with the convenience of choosing what [PSR-7](https://packagist.org/providers/psr/http-message-implementation) and [HTTP Client](https://packagist.org/providers/psr/http-client-implementation) you want to use.

To get started quickly, 

```bash
composer require frain/convoy symfony/http-client nyholm/psr7
```

## Generated API client (`Convoy\Client`)

The `Convoy\Client` namespace (`src/Client/`) is generated from Convoy's
OpenAPI spec via [OpenAPI Generator](https://openapi-generator.tech/) and
covers the full `/api/v1` surface with typed models (Guzzle-based):

```php
use Convoy\Client\Api\EventsApi;
use Convoy\Client\Configuration;
use Convoy\Client\Model\ModelsCreateEvent;

$config = (new Configuration())
    ->setHost('https://us.getconvoy.cloud/api')
    ->setAccessToken($apiKey); // the client adds the Bearer prefix

// Pin the API version this client was generated from.
$http = new \GuzzleHttp\Client([
    'headers' => ['X-Convoy-Version' => '2025-11-24'],
]);

$events = new EventsApi($http, $config);
$events->createEndpointEvent($projectId, (new ModelsCreateEvent())
    ->setEndpointId('endpoint-id')
    ->setEventType('invoice.paid')
    ->setData(['amount' => 100, 'currency' => 'USD']));
```

Do not edit `src/Client/` by hand; regenerate with `./scripts/generate.sh`
(CI on `frain-dev/convoy` dispatches this when the spec changes). The
hand-written SDK below (incl. webhook verify) is never touched by generation.

### Setup Client

Set up the client with your instance URL, API key, and project ID. Both the API key and project ID are available from your **Project Settings** page.

```php
use Convoy\Convoy;

$convoy = new Convoy([
    "uri" => "https://us.getconvoy.cloud/api/v1",
    "api_key" => "your_api_key",
    "project_id" => "your_project_id"
]);
```

Your instance URL depends on where your project lives:

- Convoy Cloud (US): `https://us.getconvoy.cloud/api/v1`
- Convoy Cloud (EU): `https://eu.getconvoy.cloud/api/v1`
- Self-hosted: `https://your-instance/api/v1`

### Create an Endpoint

An endpoint represents a target URL to receive events.

```php
$endpointData = [
    "name" => "default-endpoint",
    "url" => "https://example.com/webhooks/convoy",
    "description" => "Default Endpoint",
    "secret" => "endpoint-secret"
];

$response = $convoy->endpoints()->create($endpointData);
$endpointId = $response["data"]["uid"];
```

### Create a Subscription

Subscriptions route events from a source to an endpoint.

```php
$subscriptionData = [
    "name" => "event-sub",
    "endpoint_id" => $endpointId
];

$response = $convoy->subscriptions()->create($subscriptionData);
```

### Sending an Event

To send an event, you'll need the `uid` from the endpoint we created earlier.

```php
$eventData = [
    "endpoint_id" => $endpointId,
    "event_type" => "payment.success",
    "data" => [
        "status" => "Completed",
        "description" => "Transaction Successful"
    ]
];

$response = $convoy->events()->create($eventData);
```

To fan an event out to all endpoints with the same `owner_id`, or broadcast to every endpoint in the project:

```php
$response = $convoy->events()->fanout(["owner_id" => "owner-1", "event_type" => "payment.success", "data" => []]);
$response = $convoy->events()->broadcast(["event_type" => "payment.success", "data" => []]);
```

### Verifying Webhook Signatures

Verify with the raw request body, before parsing it. Always check the return value: `verify` returns `false` for an invalid simple signature, and throws `WebhookVerificationException` for invalid advanced signatures and malformed headers.

```php
use Convoy\Webhook;

$webhook = new Webhook("endpoint-secret");

try {
    $valid = $webhook->verify($rawRequestBody, $_SERVER["HTTP_X_CONVOY_SIGNATURE"]);
} catch (\Convoy\Exceptions\WebhookVerificationException $e) {
    $valid = false;
}

if ($valid !== true) {
    http_response_code(400);
    exit;
}

// signature is valid; process the event
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
