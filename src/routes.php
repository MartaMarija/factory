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
    $routeVersion . '/users/twig',
    [new UserController(), 'getUsersTwig']
);

Router::addRoute(
    Request::METHOD_GET,
    $routeVersion . '/users/{id}',
    [new UserController(), 'getUserById']
);

Router::addRoute(
    Request::METHOD_POST,
    $routeVersion . '/users/{id}',
    [new UserController(), 'updateUserEmail']
);

Router::addRoute(
    Request::METHOD_POST,
    $routeVersion . '/users',
    [new UserController(), 'addUser']
);

Router::addRoute(
    Request::METHOD_DELETE,
    $routeVersion . '/users/soft/{id}',
    [new UserController(), 'softDeleteUser']
);

Router::addRoute(
    Request::METHOD_DELETE,
    $routeVersion . '/users/{id}',
    [new UserController(), 'deleteUser']
);
