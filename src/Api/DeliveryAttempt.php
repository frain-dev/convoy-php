<?php

namespace Convoy\Api;

class DeliveryAttempt extends AbstractApi
{
    public function all(string $eventDeliveryId, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/eventdeliveries/%s/deliveryattempts',  $eventDeliveryId), $parameters);
    }

    public function find(string $eventDeliveryId, string $deliveryAttemptId, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/eventdeliveries/%s/deliveryattempts/%s', $eventDeliveryId, $deliveryAttemptId), $parameters);
    }
}
