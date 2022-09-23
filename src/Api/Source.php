<?php

namespace Convoy\Api;

class Source extends AbstractApi
{
    public function all(array $parameters = [])
    {
        return $this->httpGet('/sources', $parameters);
    }

    public function create(array $data, array $parameters = []): array
    {
        return $this->httpPost('/sources', $data, $parameters);
    }

    public function find(string $id, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/sources/%s', $id), $parameters);
    }

    public function update(string $id, array $data, array $parameters = []): array
    {
        return $this->httpPut(sprintf("/sources/%s", $id), $data, $parameters);
    }

    public function delete(string $id, array $data = [], array $parameters = []): array
    {
        return $this->httpDelete(sprintf('/sources/%s', $id), $data, $parameters);
    }
}
