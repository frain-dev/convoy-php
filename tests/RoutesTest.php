<?php

use Convoy\Convoy;
use Convoy\HttpClient\ClientBuilder;
use Http\Mock\Client as MockClient;
use Nyholm\Psr7\Response;

function makeConvoy(MockClient $mock): Convoy
{
    $mock->setDefaultResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
        'status' => true,
        'message' => 'ok',
        'data' => null,
    ])));

    return new Convoy([
        'api_key' => 'test-api-key',
        'uri' => 'https://us.getconvoy.cloud/api/v1',
        'project_id' => 'test-project-id',
        'client_builder' => new ClientBuilder($mock),
    ]);
}

const ROUTES_BASE = 'https://us.getconvoy.cloud/api/v1/projects/test-project-id';

test('batchResend posts an empty body with query filters', function () {
    $mock = new MockClient();
    makeConvoy($mock)->eventDeliveries()->batchResend(['status' => 'Failure']);

    $request = $mock->getRequests()[0];
    expect($request->getMethod())->toBe('POST');
    expect((string) $request->getUri())->toBe(ROUTES_BASE . '/eventdeliveries/batchretry?status=Failure');
    expect((string) $request->getBody())->toBe('');
});

test('forceResend posts an ids body', function () {
    $mock = new MockClient();
    makeConvoy($mock)->eventDeliveries()->forceResend(['ids' => ['ed-1']]);

    $request = $mock->getRequests()[0];
    expect($request->getMethod())->toBe('POST');
    expect((string) $request->getUri())->toBe(ROUTES_BASE . '/eventdeliveries/forceresend');
    expect((string) $request->getBody())->toBe('{"ids":["ed-1"]}');
});

test('endpoint pause uses PUT', function () {
    $mock = new MockClient();
    makeConvoy($mock)->endpoints()->pause('ep-1');

    $request = $mock->getRequests()[0];
    expect($request->getMethod())->toBe('PUT');
    expect((string) $request->getUri())->toBe(ROUTES_BASE . '/endpoints/ep-1/pause');
});

test('endpoint expireSecret uses PUT', function () {
    $mock = new MockClient();
    makeConvoy($mock)->endpoints()->expireSecret('ep-1', ['expiration' => 24]);

    $request = $mock->getRequests()[0];
    expect($request->getMethod())->toBe('PUT');
    expect((string) $request->getUri())->toBe(ROUTES_BASE . '/endpoints/ep-1/expire_secret');
    expect((string) $request->getBody())->toBe('{"expiration":24}');
});

test('broadcast event posts to /events/broadcast', function () {
    $mock = new MockClient();
    makeConvoy($mock)->events()->broadcast(['event_type' => 'x', 'data' => []]);

    $request = $mock->getRequests()[0];
    expect($request->getMethod())->toBe('POST');
    expect((string) $request->getUri())->toBe(ROUTES_BASE . '/events/broadcast');
});

test('fanout event posts to /events/fanout', function () {
    $mock = new MockClient();
    makeConvoy($mock)->events()->fanout(['owner_id' => 'o-1', 'event_type' => 'x', 'data' => []]);

    $request = $mock->getRequests()[0];
    expect($request->getMethod())->toBe('POST');
    expect((string) $request->getUri())->toBe(ROUTES_BASE . '/events/fanout');
});

test('requests carry auth and pinned API version headers', function () {
    $mock = new MockClient();
    makeConvoy($mock)->events()->create(['endpoint_id' => 'ep-1', 'event_type' => 'x', 'data' => []]);

    $request = $mock->getRequests()[0];
    expect($request->getHeaderLine('Authorization'))->toBe('Bearer test-api-key');
    expect($request->getHeaderLine('X-Convoy-Version'))->toBe('2025-11-24');
});
