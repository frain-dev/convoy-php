<?php

namespace Convoy\Api;

class Event extends AbstractApi
{
    public function all(array $parameters = []): array
    {
        return $this->httpGet('/events', $parameters);
    }

    public function create(array $data): array
    {
        return $this->httpPost('/events', $data);
    }

    public function find(string $id, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/events/%s', $id), $parameters);
    }
}
