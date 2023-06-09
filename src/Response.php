<?php

namespace App;

class Response implements ResponseInterface
{
    public const HTTP_OK = 200;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    
    public function __construct(private string $message, private int $code = Response::HTTP_OK)
    {
    }
    
    public function send(): string
    {
        http_response_code($this->code);
        return $this->message;
    }
}
