<?php

namespace App;

interface RequestInterface
{
    function getHeaders(): array;
    
    function getParams(): array;
    
    function getBody(): array;
    
    function getHeadersValue(string $key): string;
    
    function getBodyValue(string $key): string;
    
    function getParamsValue(string $key): string;
}