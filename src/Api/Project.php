<?php

namespace Convoy\Api;

class Project extends AbstractApi
{
    public function find(array $parameters = []): array
    {
        return $this->httpGet("/", $parameters);
    }

    public function update(array $data, array $parameters = []): array
    {
        return $this->httpPut("/", $data, $parameters);
    }

    public function delete(array $data = [], array $parameters = []): array
    {
        return $this->httpDelete("/", $data, $parameters);
    }
}
