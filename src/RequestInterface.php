<?php

namespace App;

interface RequestInterface
{
    public function getHeaders(): array;

    public function getParams(): array;

    public function getBody(): array;

    public function getHeadersValue(string $key): string;

    public function getBodyValue(string $key): string;

    public function getParamsValue(string $key): string;
}
