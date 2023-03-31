<?php

namespace App;

class AppError extends \Error
{
    public function __construct(public $statusCode, string $message)
    {
        parent::__construct($message, $statusCode);
        http_response_code($this->statusCode);
    }
}
