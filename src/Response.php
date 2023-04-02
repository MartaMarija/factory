<?php

namespace App;

class Response implements ResponseInterface
{
    public const HTTP_OK = 200;
    public const HTTP_NOT_FOUND = 404;

    public function __construct(private string $message)
    {
    }

    public function send(): string
    {
        return $this->message;
    }
}
