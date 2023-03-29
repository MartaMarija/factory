<?php

use App\v1\RequestInterface;
use App\v1\Router;


$router = Router::getRouter();

$router->addRoute("GET", "/index.php", function (RequestInterface $request) {
    return "GET methoda: ime => " . $request->getParamsValue("ime");
});

$router->addRoute("POST", "/index.php", function (RequestInterface $request) {
    return "POST methoda: ime => " . $request->getParamsValue("ime");
});



