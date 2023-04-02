<?php

use App\Router;
use App\Request;
use App\AppError;
use App\Controllers\UserController;

$routeVersion = '/api/v1';

Router::addRoute(
    Request::METHOD_GET,
    $routeVersion . '/users/{userName}/age',
    [new UserController, 'getUserAge']
);

//Router::addRoute(
//    "GET",
//    $routeName . "/users/{userName}/favColor",
//    function (RequestInterface $request): ResponseInterface {
//        $controller = new UserController();
//        $userName = $request->getParamsValue("userName");
//        $user = $controller->getUserByName($userName);
//        if ($user == null) {
//            throw new AppError(404, "User '" . $userName . "' not found!");
//        }
//        return new Response($user['name'] . "'s favourite color is " . $user['favColor'] . ".");
//    }
//);
