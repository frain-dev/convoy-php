<?php

namespace Convoy;

use Convoy\Exceptions\WebhookVerificationException;

class Webhook
{
    public const DEFAULT_TOLERANCE = 300;
    public const DEFAULT_ENCODING = 'hex';
    public const DEFAULT_HASH = 'sha256';

    public string $secret;

    public int $tolerance;

    public string $hash;

    public string $encoding;

    public function __construct($secret, $hash = self::DEFAULT_HASH, $encoding = self::DEFAULT_ENCODING, $tolerance = self::DEFAULT_TOLERANCE)
    {
        $this->secret = $secret;

        $this->hash = $hash;

        $this->encoding = $encoding;

        $this->tolerance = $tolerance;
    }

    public function verify(string $payload, string $header): bool
    {
        $parts = explode(',', $header);

        return count($parts) > 1 ? $this->verifyAdvancedSignature($payload, $header) : $this->verifySimpleSignature($payload, $header);
    }

    private function verifyAdvancedSignature(string $payload, string $header): bool
    {
        $timestamp = $this->getTimestamp($header);

        $signatures = $this->getSignatures($header);

        if ($timestamp === -1) {
            throw new WebhookVerificationException('Webhook has invalid header');
        }

        $tolerance = $this->tolerance;

        if (($tolerance > 0) && (abs(time()) - $timestamp) > $tolerance) {
            throw new WebhookVerificationException('Timestamp has expired');
        }

        $signedPayload = "{$timestamp},{$payload}";
        $expectedSignature = $this->computeSignature($signedPayload);
        $signatureFound = false;

        foreach ($signatures as $signature) {
            if ($this->secureCompare($expectedSignature, $signature)) {
                $signatureFound = true;

                break;
            }
        }

        if (! $signatureFound) {
            throw new WebhookVerificationException('Webhook has no valid signature');
        }

        return true;
    }

    private function getTimestamp(string $header): int
    {
        $items = explode(',', $header);

        foreach ($items as $item) {
            $itemParts = explode('=', $item, 2);
            if ('t' === $itemParts[0]) {
                if (! is_numeric($itemParts[1])) {
                    return -1;
                }

                return (int) ($itemParts[1]);
            }
        }

        return -1;
    }

    private function getSignatures(string $header): array
    {
        $items = explode(',', $header);

        $signatures = [];

        foreach ($items as $item) {
            $itemParts = explode('=', $item, 2);
            if (str_contains(trim($itemParts[0]), "v")) {
                $signatures[] = $itemParts[1];
            }
        }

        return $signatures;
    }

    private function verifySimpleSignature(string $payload, string $header): bool
    {
        $signature = $this->computeSignature($payload, $header);

        return $this->secureCompare($this->computeSignature($payload), $header);
    }

    private function computeSignature(string $payload)
    {
        switch ($this->encoding) {
            case 'hex':
                return hash_hmac($this->hash, $payload, $this->secret);
            case 'base64':
                return base64_encode(hash_hmac($this->hash, $payload, $this->secret, true));
            default:
                throw new WebhookVerificationException('Invalid Encoding');
        }
    }

    private function secureCompare(string $a, string $b): bool
    {
        return hash_equals($a, $b);
    }
}
