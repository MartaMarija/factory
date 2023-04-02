<?php

namespace App;

class JsonResponse implements ResponseInterface
{
    public function __construct(private mixed $data, private int $statusCode = Response::HTTP_OK)
    {
    }
    
    public function send(): string
    {
        header('Content-Type: application/json');
        http_response_code($this->statusCode);
        return json_encode($this->data);
    }
}
