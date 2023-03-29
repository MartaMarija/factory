<?php

namespace App;

use Couchbase\BadInputException;

class Request implements RequestInterface
{
    private array $headers;
    private array $params;
    private array $body;
    
    public function __construct(array $headers, array $params, array $body)
    {
        $this->headers = $headers;
        $this->params = $params;
        $this->body = $body;
    }
    
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    public function getParams(): array
    {
        return $this->params;
    }
    
    public function getBody(): array
    {
        return $this->body;
    }
    
    function getHeadersValue(string $key): string
    {
        if (!array_key_exists($key, $this->headers)) {
            //TODO throw Error
            return "Ne postoji";
        }
        return $this->headers[$key];
    }
    
    function getBodyValue(string $key): string
    {
        if (!array_key_exists($key, $this->body)) {
            //TODO throw Error
            return "Ne postoji";
        }
        return $this->body[$key];
    }
    
    function getParamsValue(string $key): string
    {
        if (!array_key_exists($key, $this->params)) {
            //TODO throw Error
            return "Ne postoji";
        }
        return $this->params[$key];
    }
}