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

Router::addRoute(
    Request::METHOD_GET,
    $routeVersion . '/users/{id}',
    [new UserController(), 'getUserById']
);

Router::addRoute(
    Request::METHOD_POST,
    $routeVersion . '/users',
    [new UserController(), 'addUsers']
);
