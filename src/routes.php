<?php

use App\Controllers\UserController;
use App\Request;
use App\Router;

$routeVersion = '/api/v1';

Router::addRoute(
    Request::METHOD_GET,
    $routeVersion . '/users',
    [new UserController(), 'getUsers']
);

//Router::addRoute(
//    Request::METHOD_GET,
//    $routeVersion . '/users/{userName}/age',
//    [new UserController(), 'getUserAge']
//);