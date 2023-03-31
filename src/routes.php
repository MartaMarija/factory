<?php

use App\RequestInterface;
use App\Router;
use App\ResponseInterface;
use App\Response;
use App\AppError;
use App\controllers\UserController;

$routeName = "/api/v1";

Router::addRoute("GET", $routeName . "/users/{userName}/age", function (RequestInterface $request): ResponseInterface {
    $controller = new UserController();
    $userName = $request->getParamsValue("userName");
    $user = $controller->getUserByName($userName);
    if ($user == null) {
        throw new AppError(404, "User '" . $userName . "' not found!");
    }
    return new Response($user['name'] . " is " . $user['age'] . " years old.");
});

Router::addRoute("GET", $routeName . "/users/{userName}/favColor", function (RequestInterface $request): ResponseInterface {
    $controller = new UserController();
    $userName = $request->getParamsValue("userName");
    $user = $controller->getUserByName($userName);
    if ($user == null) {
        throw new AppError(404, "User '" . $userName . "' not found!");
    }
    return new Response($user['name'] . "'s favourite color is " . $user['favColor'] . ".");
});
