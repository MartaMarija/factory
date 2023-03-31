<?php

namespace App;

class Request implements RequestInterface
{
    public array $headers;
    public array $params;
    public array $body;

    public function __construct()
    {
        $this->headers = apache_request_headers();
        $this->params = $_GET;
        $this->body = $_POST;
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

    public function getHeadersValue(string $key): string
    {
        if (!array_key_exists($key, $this->headers)) {
            //TODO throw Error
            return "Ne postoji";
        }
        return $this->headers[$key];
    }

    public function getBodyValue(string $key): string
    {
        if (!array_key_exists($key, $this->body)) {
            //TODO throw Error
            return "Ne postoji";
        }
        return $this->body[$key];
    }

    public function getParamsValue(string $key): string
    {
        if (!array_key_exists($key, $this->params)) {
            //TODO throw Error
            return "Ne postoji";
        }
        return $this->params[$key];
    }
}
