<?php

namespace Convoy\Api;

class Application extends AbstractApi
{
    public function all(array $parameters = [])
    {
        return $this->httpGet('/applications', $parameters);
    }

    public function create(array $data): array
    {
        return $this->httpPost('/applications', $data);
    }

    public function find(string $id, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/applications/%s', $id), $parameters);
    }

    public function update(string $id, array $data): array
    {
        return $this->httpPut(sprintf('/applications/%s', $id), $data);
    }

    public function delete(string $id, array $data = []): array
    {
        return $this->httpDelete(sprintf('/applications/%s', $id), $data);
    }
}
