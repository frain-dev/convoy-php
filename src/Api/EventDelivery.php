<?php

namespace Convoy\Api;

class EventDelivery extends AbstractApi
{
    public function all(array $parameters = []): array
    {
        return $this->httpGet('/eventdeliveries', $parameters);
    }

    public function find(string $id, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/eventdeliveries/%s', $id), $parameters);
    }

    public function resend(string $id, array $parameters = []): array
    {
        return $this->httpPut(sprintf('/eventdeliveries/%s/resend', $id), [], $parameters);
    }

    /**
     * Batch retries deliveries matching the query filters; the server reads
     * filters from query params only (e.g. endpointId, eventId, status).
     */
    public function batchResend(array $parameters = []): array
    {
        return $this->httpPost('/eventdeliveries/batchretry', [], $parameters);
    }

    public function forceResend(array $data, array $parameters = []): array
    {
        return $this->httpPost('/eventdeliveries/forceresend', $data, $parameters);
    }
}
