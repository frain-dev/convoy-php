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

    public function resend(string $id): array
    {
        return $this->httpPut(sprintf('/eventdeliveries/%s/resend', $id));
    }

    public function batchResend(string $id, array $data): array
    {
        return $this->httpPost('/eventdeliveries/batchretry', $data);
    }
}
