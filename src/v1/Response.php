<?php

namespace App\v1;

class Response implements ResponseInterface
{
    public string $message;
    
    public function __construct(string $message)
    {
        $this->message = $message;
    }
    
    function send(): string
    {
        return $this->message;
    }
}