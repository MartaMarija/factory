<?php

namespace App;

class AppError extends \Error
{
    public function __construct(public $code, string $message)
    {
        parent::__construct($message, $code);
        http_response_code($this->code);
    }
}
