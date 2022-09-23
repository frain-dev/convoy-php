<?php

namespace Convoy\Api;

class Subscription extends AbstractApi
{
    public function all(array $parameters = [])
    {
        return $this->httpGet('/subscriptions', $parameters);
    }

    public function create(array $data, array $parameters = []): array
    {
        return $this->httpPost('/subscriptions', $data, $parameters);
    }

    public function find(string $id, array $parameters = []): array
    {
        return $this->httpGet(sprintf('/subscriptions/%s', $id), $parameters);
    }

    public function update(string $id, array $data, array $parameters = []): array
    {
        return $this->httpPut(sprintf("/subscriptions/%s", $id), $data, $parameters);
    }

    public function delete(string $id, array $data = [], array $parameters = []): array
    {
        return $this->httpDelete(sprintf('/subscriptions/%s', $id), $data, $parameters);
    }
}
