<?php

namespace App;

interface RequestInterface
{
    public function getHeadersValue(string $key): string;
    
    public function getMethod(): string;
    
    public function getUrlParts(): array;
    
    public function getParams(): array;
    
    public function getParam(string $key): string|array;
    
    public function addParam(string $key, string $value): void;
}
