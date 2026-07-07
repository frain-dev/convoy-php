<?php

namespace Convoy\Api;

class Event extends AbstractApi
{
    public function all(array $parameters = []): array
    {
        return $this->httpGet('/events', $parameters);
    }

    public function create(array $data, array $parameters = []): array
    {
        return $this->httpPost('/events', $data, $parameters);
    }

    public function find(string $id, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/events/%s', $id), $parameters);
    }

    public function fanout(array $data, array $parameters = []): array
    {
        return $this->httpPost('/events/fanout', $data, $parameters);
    }

    public function broadcast(array $data, array $parameters = []): array
    {
        return $this->httpPost('/events/broadcast', $data, $parameters);
    }

    public function replay(string $id): array
    {
        return $this->httpPut(sprintf('/events/%s/replay', $id));
    }
}
