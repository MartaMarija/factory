<?php

use App\RequestInterface;
use App\Router;
use App\ResponseInterface;
use App\Response;

Router::addRoute("GET", "/index.php", function (RequestInterface $request): ResponseInterface {
    return new Response("GET methoda: ime => " . $request->getParamsValue("ime"));
});

Router::addRoute("POST", "/index.php", function (RequestInterface $request): ResponseInterface {
    return new Response("POST methoda: ime => " . $request->getParamsValue("ime"));
});
