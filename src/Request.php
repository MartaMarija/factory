<?php

namespace App;

class Request implements RequestInterface
{
    public array $headers;
    public array $params;
    public array $body;
    public string $method;
    public array $url;

    public function __construct()
    {
        $this->headers = apache_request_headers();
        $this->params = $_GET;
        $this->body = $_POST;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->url = explode('/', $url);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function addParam(string $key, string $value): void
    {
        $this->params[$key] = $value;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUrl(): array
    {
        return $this->url;
    }

    public function getHeadersValue(string $key): string
    {
        if (!array_key_exists($key, $this->headers)) {
            throw new AppError(404, "'$key' not found!");
        }
        return $this->headers[$key];
    }

    public function getBodyValue(string $key): string
    {
        if (!array_key_exists($key, $this->body)) {
            throw new AppError(404, "'$key' not found!");
        }
        return $this->body[$key];
    }

    public function getParamsValue(string $key): string
    {
        if (!array_key_exists($key, $this->params)) {
            throw new AppError(404, "'$key' not found!");
        }
        return $this->params[$key];
    }
}
