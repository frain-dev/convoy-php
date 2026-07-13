<?php

use Convoy\Exceptions\WebhookVerificationException;
use Convoy\Webhook;

// signature-vectors.json is generated from the server signing code
// (convoy/pkg/signature) and vendored here so this SDK verifies against the same
// canonical set as every other Convoy SDK. Regenerate upstream with
// CONVOY_WRITE_VECTORS=1 go test ./pkg/signature -run TestGenerateSignatureVectors
$vectors = json_decode(file_get_contents(__DIR__ . '/signature-vectors.json'), true);

foreach ($vectors as $vec) {
    test('shared vector: ' . $vec['name'], function () use ($vec) {
        // hash_hmac algorithm names are lowercase; the vectors use SHA256/SHA512.
        $webhook = new Webhook(
            $vec['secret'],
            strtolower($vec['hash']),
            $vec['encoding'],
            $vec['tolerance']
        );

        if ($vec['valid']) {
            expect($webhook->verify($vec['payload'], $vec['header']))->toBeTrue();
        } else {
            // Fail closed: simple mode returns false, advanced mode throws.
            $accepted = false;
            try {
                $accepted = $webhook->verify($vec['payload'], $vec['header']) === true;
            } catch (WebhookVerificationException $e) {
                $accepted = false;
            }
            expect($accepted)->toBeFalse();
        }
    });
}

// Guards the parse tightening: only keys starting with "v" carry a signature. A
// real signature under any other key must be rejected, not accepted because the
// key happens to contain "v".
test('advanced signature key must start with v', function () {
    $secret = 'convoy-webhook-secret';
    $payload = '{"m":1}';
    $ts = time();
    $sig = hash_hmac('sha256', "{$ts},{$payload}", $secret);

    $webhook = new Webhook($secret, 'sha256', 'hex');

    expect($webhook->verify($payload, "t={$ts},v1={$sig}"))->toBeTrue();

    $accepted = false;
    try {
        $accepted = $webhook->verify($payload, "t={$ts},nav={$sig}") === true;
    } catch (WebhookVerificationException $e) {
        $accepted = false;
    }
    expect($accepted)->toBeFalse();
});
