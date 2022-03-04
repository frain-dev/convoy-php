<?php

namespace Convoy\Api;

class DeliveryAttempt extends AbstractApi
{
    public function all(string $eventDeliveryId): array
    {
        return $this->httpGet(sprintf('/eventdeliveries/%s/deliveryattempts',  $eventDeliveryId));
    }

    public function find(string $eventDeliveryId, string $deliveryAttemptId): array
    {
        return $this->httpGet(sprintf('/eventdeliveries/%s/deliveryattempts/%s', $eventDeliveryId, $deliveryAttemptId));
    }
}
