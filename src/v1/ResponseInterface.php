<?php

namespace App\v1;

interface ResponseInterface
{
    function send(): string;
}