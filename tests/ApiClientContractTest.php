<?php

use Convoy\Client\Api\EventsApi;
use Convoy\Client\Configuration;
use Convoy\Client\Model\DatastoreEvent;
use Convoy\Client\Model\ModelsCreateEvent;
use Convoy\Client\ObjectSerializer;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

// Offline route-contract test: proves the generated client sends the verb,
// path, auth header, and JSON body the Convoy server expects, and that
// arbitrary event data payloads round-trip without dropping keys.
test('create endpoint event sends expected request', function () {
    $captured = [];
    $mock = new MockHandler([
        new Response(201, ['Content-Type' => 'application/json'],
            '{"status":true,"message":"ok","data":null}'),
    ]);
    $stack = HandlerStack::create($mock);
    $stack->push(Middleware::history($captured));

    $config = (new Configuration())
        ->setHost('https://us.getconvoy.cloud/api')
        // spec now models auth as http bearer; the client adds the Bearer prefix
        ->setAccessToken('test-key');

    $event = (new ModelsCreateEvent())
        ->setEndpointId('ep-1')
        ->setEventType('invoice.paid')
        ->setData([
            'amount' => 100,
            'currency' => 'USD',
            'nested' => ['customer' => 'cus_123'],
        ]);

    // Guzzle applies default headers to sent requests only when the request
    // doesn't already carry them; the version pin rides along this way.
    $http = new Client([
        'handler' => $stack,
        'headers' => ['X-Convoy-Version' => '2025-11-24'],
    ]);
    (new EventsApi($http, $config))->createEndpointEvent('proj-1', $event);

    expect($captured)->toHaveCount(1);
    $request = $captured[0]['request'];

    expect($request->getMethod())->toBe('POST');
    expect($request->getUri()->getPath())->toBe('/api/v1/projects/proj-1/events');
    expect($request->getHeaderLine('Authorization'))->toBe('Bearer test-key');
    expect($request->getHeaderLine('X-Convoy-Version'))->toBe('2025-11-24');
    expect($request->getHeaderLine('Content-Type'))->toContain('application/json');

    $sent = json_decode((string) $request->getBody(), true);
    expect($sent['endpoint_id'])->toBe('ep-1');
    expect($sent['event_type'])->toBe('invoice.paid');
    expect($sent['data']['amount'])->toBe(100);
    expect($sent['data']['currency'])->toBe('USD');
    expect($sent['data']['nested']['customer'])->toBe('cus_123');
});

test('event data inbound keeps all keys', function () {
    $raw = json_decode(
        '{"uid":"evt-1","event_type":"invoice.paid",'
        . '"data":{"amount":100,"nested":{"customer":"cus_123"}}}'
    );

    $event = ObjectSerializer::deserialize($raw, DatastoreEvent::class);

    $data = (array) $event->getData();
    expect($data['amount'])->toBe(100);
    expect((array) $data['nested'])->toBe(['customer' => 'cus_123']);
});
