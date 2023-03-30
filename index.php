<?php

require_once 'vendor/autoload.php';

use App\Router;
use App\Request;

include("./src/routes.php");

$router = Router::getRouter();
$request = new Request();
$response = $router->resolveRoute($request);
echo $response->send();




