<?php

namespace Convoy\Exceptions;

use Exception;

class WebhookVerificationException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
