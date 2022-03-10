<?php

namespace Convoy\Api;

use Convoy\HttpClient\ResponseHelper;
use Http\Client\Common\HttpMethodsClientInterface;

abstract class AbstractApi
{
    private HttpMethodsClientInterface $httpClient;

    public function __construct(HttpMethodsClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function httpPost(string $path, array $body = [], array $parameters = [], array $requestHeaders = []): array
    {
        if (! empty($parameters)) {
            $path .= $this->buildQueryString($parameters);
        }

        $response = $this->httpClient->post($path, $requestHeaders, $this->createJsonBody($body));

        return ResponseHelper::getContent($response);
    }

    public function httpGet(string $path, array $parameters = [], array $requestHeaders = [])
    {
        if (! empty($parameters)) {
            $path .= $this->buildQueryString($parameters);
        }

        $response = $this->httpClient->get($path, $requestHeaders);

        return ResponseHelper::getContent($response);
    }

    public function httpPut(string $path, array $body = [], array $parameters = [], array $requestHeaders = []): array
    {
        if (! empty($parameters)) {
            $path .= $this->buildQueryString($parameters);
        }

        $response = $this->httpClient->put($path, $requestHeaders, $this->createJsonBody($body));

        return ResponseHelper::getContent($response);
    }

    public function httpDelete(string $path, array $body = [], array $parameters = [], array $requestHeaders = []): array
    {
        if (! empty($parameters)) {
            $path .= $this->buildQueryString($parameters);
        }

        $response = $this->httpClient->delete($path, $requestHeaders, $this->createJsonBody($body));

        return ResponseHelper::getContent($response);
    }

    protected function createJsonBody(array $parameters)
    {
        return (count($parameters) === 0) ? null : json_encode($parameters, empty($parameters) ? JSON_FORCE_OBJECT : 0);
    }

    protected function buildQueryString(array $parameters): string
    {
        return '?' .http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
    }
}
