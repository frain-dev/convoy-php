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
        $message = sprintf("The parameters passed to the API were invalid. Check your inputs!\n\n%s", self::getJsonMessage($response));

        return new self($message, $response->getStatusCode(), $response);
    }

    public static function unauthorized(ResponseInterface $response)
    {
        return new self(self::getJsonMessage($response), $response->getStatusCode(), $response);
    }

    public static function notFound(ResponseInterface $response)
    {
        return new self(self::getJsonMessage($response), $response->getStatusCode(), $response);
    }

    public static function tooManyRequests(ResponseInterface $response)
    {
        return new self('Too many requests', $response->getStatusCode(), $response);
    }

    public static function forbidden(ResponseInterface $response)
    {
        $message = sprintf("Forbidden!\n\n%s", self::getJsonMessage($response));

        return new self($message, $response->getStatusCode(), $response);
    }

    public static function serverError(ResponseInterface $response)
    {
        return new self(self::getJsonMessage($response), $response->getStatusCode(), $response);
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    protected static function getJsonMessage(ResponseInterface $response)
    {
        $body = $response->getBody()->getContents();

        $jsonDecoded = json_decode($body, true);

        return isset($jsonDecoded['message']) ? $jsonDecoded['message'] : $body;
    }
}
