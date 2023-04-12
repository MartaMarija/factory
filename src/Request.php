<?php

namespace App;

use App\Exceptions\AppError;

class Request implements RequestInterface
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_DELETE = 'DELETE';
    private array $headers;
    private array $attributes;
    private array $query;
    private array $body;
    private string $method;
    private array $urlParts;
    
    public function __construct()
    {
        $this->headers = apache_request_headers();
        $this->attributes = [];
        $this->query = $_GET;
        if (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json')) {
            $this->body = $this->decodeJson();
        } else {
            $this->body = $_POST;
        }
        $this->method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->urlParts = explode('/', $url);
    }
    
    private function decodeJson(): array
    {
        $data = file_get_contents('php://input');
        return json_decode($data, true);
    }
    
    public function getHeadersValue(string $key): string
    {
        if (array_key_exists($key, $this->headers)) {
            return $this->headers[$key];
        }
        throw new AppError(Response::HTTP_NOT_FOUND, "Key '$key' not found!");
    }
    
    public function getMethod(): string
    {
        return $this->method;
    }
    
    public function getUrlParts(): array
    {
        return $this->urlParts;
    }
    
    public function getParams(): array
    {
        return array_merge($this->attributes, $this->query, $this->body);
    }
    
    public function getParam(string $key): string|array
    {
        $params = $this->getParams();
        if (array_key_exists($key, $params)) {
            return $params[$key];
        }
        throw new AppError(Response::HTTP_NOT_FOUND, "'$key' not found!");
    }
    
    public function addParam(string $key, string $value): void
    {
        $this->attributes[$key] = $value;
    }
}
