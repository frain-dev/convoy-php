<?php

namespace Convoy\HttpClient;

use Convoy\Exceptions\HttpClientException;
use Exception;
use InvalidJsonResponseException;
use Psr\Http\Message\ResponseInterface;

class ResponseHelper
{
    public static function getContent(ResponseInterface $response): array
    {
        if (! in_array($response->getStatusCode(), [200, 201, 202], true)) {
            self::handleErrors($response);
        }

        $content = json_decode($response->getBody()->getContents(), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidJsonResponseException(sprintf('Error (%d) when trying to json_decode response', json_last_error()));
        }

        return $content;
    }

    public static function handleErrors(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();

        switch ($statusCode) {
            case 400:
                throw HttpClientException::badRequest($response);
            case 401:
                throw HttpClientException::unauthorized($response);
            case 403:
                 throw HttpClientException::forbidden($response);
            case 404:
                throw HttpClientException::notFound($response);
            case 429:
                throw HttpClientException::tooManyRequests($response);
            case $statusCode <= 502:
               throw HttpClientException::serverError($response);
            default:
               throw new Exception();
        }
    }
}
