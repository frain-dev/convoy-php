<?php

namespace Convoy\Api;

class Endpoint extends AbstractApi
{
    public function all(array $parameters = []): array
    {
        return $this->httpGet('/endpoints', $parameters);
    }

    public function create(array $data, array $parameters): array
    {
        return $this->httpPost('/endpoints', $data, $parameters);
    }

    public function find(string $endpointId, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/endpoints/%s', $endpointId), $parameters);
    }

    public function update(string $endpointId, array $data, array $parameters = []): array
    {
        return $this->httpPut(sprintf('/endpoints/%s', $endpointId), $data, $parameters);
    }

    public function delete(string $endpointId, array $data = [], array $parameters = []): array
    {
        return $this->httpDelete(sprintf('/endpoints/%s', $endpointId), $data, $parameters);
    }
}
