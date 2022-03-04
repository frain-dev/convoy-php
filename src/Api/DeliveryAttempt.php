<?php

namespace Convoy\Api;

class DeliveryAttempt extends AbstractApi
{
    public function all(string $eventId): array
    {
        return $this->httpGet(sprintf('/events/%s/deliveryattempts', $eventId));
    }

    public function find(string $eventId, string $eventDeliveryId, string $deliveryAttemptId): array
    {
        return $this->httpGet(sprintf('/events/%s/eventdeliveries/%s/deliveryattempts/%s', $eventId, $eventDeliveryId, $deliveryAttemptId));
    }
}
