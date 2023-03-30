<?php

namespace App;

interface ResponseInterface
{
    function send(): string;
}