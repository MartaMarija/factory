<?php

use App\Router;
use App\RequestInterface;


$router = Router::getRouter();

$router->addRoute("GET", "/index.php", function (RequestInterface $request) {
    return "POST methoda: ime => " . $request->getParamsValue("ime");
});

$router->addRoute("POST", "/index.php", function (RequestInterface $request) {
    return "POST methoda: ime => " . $request->getParamsValue("ime");
});



