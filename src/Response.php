<?php

namespace App;

class Response implements ResponseInterface
{
    public function __construct(private string $message)
    {
    }

    public function send(): string
    {
        return $this->message;
    }
}
