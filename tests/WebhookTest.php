<?php

use Convoy\Exceptions\WebhookVerificationException;
use Convoy\Webhook;

test('no valid signature', function () {
    $webhook = new Webhook('random');

    $payload = '{"email":"test@gmail.com"}';

    expect($webhook->verify($payload, ''))->toBeFalse();
});

test('verify simple hex signature', function () {
    $webhook = new Webhook('8IX9njirDG', 'sha512');

    $payload = '{"email":"test@gmail.com","first_name":"test","last_name":"test"}';
    $header = '666060cbe1348bbc7ec98f4e93dda8eaaf11bbf283d6a2dd56e841b2ef12fcd465c846903f709942473e1442604798186746f04848702c44a773f80672de7b21';

    expect($webhook->verify($payload, $header))->toBeTrue();
});

test('verify simple base64 signature', function () {
    $webhook = new Webhook('8IX9njirDG', 'sha512', 'base64');

    $payload = '{"email":"test@gmail.com","first_name":"test","last_name":"test"}';
    $header = 'ZmBgy+E0i7x+yY9Ok92o6q8Ru/KD1qLdVuhBsu8S/NRlyEaQP3CZQkc+FEJgR5gYZ0bwSEhwLESnc/gGct57IQ==';

    expect($webhook->verify($payload, $header))->toBeTrue();
});

test('invalid signature header', function () {
    $webhook = new Webhook('8IX9njirDG', 'sha512', 'base64');

    $payload = '{"email":"test@gmail.com","first_name":"test","last_name":"test"}';
    $header = 'd33C9sJXVO4CnE1hisHHQzUf0inr5KWJH7T8+zvgATTWEgAq5vErZR/xihDXqtok5ubv77xGP/RE++NphZnWLg==';

    expect($webhook->verify($payload, $header))->toBeFalse();
});

test('verify advanced hex signature', function () {
    $webhook = new Webhook('Convoy');

    $payload = '{"email":"test@gmail.com"}';
    $header = 't=2048976161,v1=c6c39e1bd410fc1dc4db90e97039f006d088c950a275296767595d330195088f,v1=6594ee0713f1cc1f54c3f713d06a60718cd10949c7684412f159034d49621e07';

    expect($webhook->verify($payload, $header))->toBeTrue();
});

test('verify advanced base64 signature', function () {
    $webhook = new Webhook('8IX9njirDG', 'sha256', 'base64');

    $payload = '{"email":"test@gmail.com"}';
    $header = 't=2048976161,v1=afdb90313acfa15a3fc425755ae651a204947710315bb2a90bccaa87fce88998,v1=fLBDCBUiX5iIs0L5zfNq45h23EkX1HAMpFF+2lHrnes=';

    expect($webhook->verify($payload, $header))->toBeTrue();
});

it('invalid timestamp header', function () {
    $webhook = new Webhook('8IX9njirDG', 'sha256', 'base64');

    $payload = '{"email":"test@gmail.com"}';
    $header = 't=2202-1-1,v1=U5yBiZmFYHiom0A5hEnfLPCoQzndno4ocR45W/zkO+w=';

    $webhook->verify($payload, $header);
})->throws(WebhookVerificationException::class, 'Webhook has invalid header');
