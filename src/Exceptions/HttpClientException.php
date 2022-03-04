<?php

namespace Convoy\Exceptions;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class HttpClientException extends RuntimeException
{
    private ResponseInterface $response;
    private int $responseCode;

    public function __construct(string $message, int $code, ResponseInterface $response)
    {
        parent::__construct($message, $code);

        $this->response = $response;
        $this->responseCode = $response->getStatusCode();
    }

    public static function badRequest(ResponseInterface $response)
    {
        $body = $response->getBody()->getContents();

        $jsonDecoded = json_decode($body, true);

        $validationMessage = isset($jsonDecoded['message']) ? $jsonDecoded['message'] : $body;

        $message = sprintf("The parameters passed to the API were invalid. Check your inputs!\n\n%s", $validationMessage);

        return new self($message, $response->getStatusCode(), $response);
    }

    public static function unauthorized(ResponseInterface $response)
    {
        return new self('Unauthorized credentials', 401, $response);
    }

    public static function notFound(ResponseInterface $response)
    {
        return new self('The endpoint you have tried to access does not exist', $response->getStatusCode(), $response);
    }

    public static function tooManyRequests(ResponseInterface $response)
    {
        return new self('Too many requests', $response->getStatusCode(), $response);
    }

    public static function forbidden(ResponseInterface $response)
    {
        $body = $response->getBody()->getContents();

        $jsonDecoded = json_decode($body, true);

        $validationMessage = isset($jsonDecoded['message']) ? $jsonDecoded['message'] : $body;

        $message = sprintf("Forbidden!\n\n%s", $validationMessage);

        return new self($message, $response->getStatusCode(), $response);
    }

    public static function serverError(ResponseInterface $response)
    {
        $body = $response->getBody()->getContents();

        $jsonDecoded = json_decode($body, true);

        $message = isset($jsonDecoded['message']) ? $jsonDecoded['message'] : $body;

        return new self($message, $response->getStatusCode(), $response);
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }
}
