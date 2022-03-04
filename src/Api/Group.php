<?php

namespace Convoy\Api;

class Group extends AbstractApi
{
    public function all(array $parameters = [])
    {
        return $this->httpGet('/groups', $parameters);
    }

    public function create(array $data): array
    {
        return $this->httpPost('/groups', $data);
    }

    public function find(string $id, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/groups/%s', $id), $parameters);
    }

    public function update(string $id, array $data): array
    {
        return $this->httpPut(sprintf('/groups/%s', $id), $data);
    }

    public function delete(string $id, array $data = []): array
    {
        return $this->httpDelete(sprintf('/groups/%s', $id), $data);
    }
}
