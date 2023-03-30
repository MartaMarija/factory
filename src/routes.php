<?php

use App\RequestInterface;
use App\Router;
use App\ResponseInterface;
use App\Response;


$router = Router::getRouter();

$router->addRoute("GET", "/index.php", function (RequestInterface $request): ResponseInterface {
    return new Response("GET methoda: ime => " . $request->getParamsValue("ime"));
});

$router->addRoute("POST", "/index.php", function (RequestInterface $request): ResponseInterface {
    return new Response("POST methoda: ime => " . $request->getParamsValue("ime"));
});



