<?php

namespace Convoy\Api;

class Endpoint extends AbstractApi
{
    public function all(string $appId, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/applications/%s/endpoints', $appId), $parameters);
    }

    public function create(string $appId, array $data): array
    {
        return $this->httpPost(sprintf('/applications/%s/endpoints', $appId), $data);
    }

    public function find(string $appId, string $endpointId, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/applications/%s/endpoints/%s', $appId, $endpointId), $parameters);
    }

    public function update(string $appId, string $endpointId, array $data): array
    {
        return $this->httpPut(sprintf('/applications/%s/endpoints/%s', $appId, $endpointId), $data);
    }

    public function delete(string $appId, string $endpointId, array $data = []): array
    {
        return $this->httpDelete(sprintf('/applications/%s/endpoints/%s', $appId, $endpointId), $data);
    }
}
