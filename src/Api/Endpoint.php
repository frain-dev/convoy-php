<?php

namespace Convoy\Api;

class Endpoint extends AbstractApi
{
    public function all(string $appId, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/applications/%s/endpoints', $appId), $parameters);
    }

    public function create(string $appId, array $data, array $parameters): array
    {
        return $this->httpPost(sprintf('/applications/%s/endpoints', $appId), $data, $parameters);
    }

    public function find(string $appId, string $endpointId, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/applications/%s/endpoints/%s', $appId, $endpointId), $parameters);
    }

    public function update(string $appId, string $endpointId, array $data, array $parameters = []): array
    {
        return $this->httpPut(sprintf('/applications/%s/endpoints/%s', $appId, $endpointId), $data, $parameters);
    }

    public function delete(string $appId, string $endpointId, array $data = [], array $parameters = []): array
    {
        return $this->httpDelete(sprintf('/applications/%s/endpoints/%s', $appId, $endpointId), $data, $parameters);
    }
}
