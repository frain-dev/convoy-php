<?php

namespace Convoy\Api;

class Project extends AbstractApi
{
    public function find(string $id, array $parameters = []): array
    {
        return $this->httpGet("/", $parameters);
    }

    public function update(string $id, array $data, array $parameters = []): array
    {
        return $this->httpPut("/", $data, $parameters);
    }

    public function delete(string $id, array $data = [], array $parameters = []): array
    {
        return $this->httpDelete("/", $data, $parameters);
    }
}
