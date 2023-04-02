<?php

namespace App;

interface RequestInterface
{
    public function getHeaders(): array;
    
    public function getParams(): array;
    
    public function addParam(string $key, string $value): void;
    
    public function getBody(): array;
    
    public function getMethod(): string;
    
    public function getUrlParts(): array;
    
    public function getHeadersValue(string $key): string;
    
    public function getBodyValue(string $key): string;
    
    public function getParamsValue(string $key): string;
}
