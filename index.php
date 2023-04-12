<?php

require_once 'vendor/autoload.php';

use App\Exceptions\AppError;
use App\Request;
use App\Router;

include("./src/routes.php");

$request = new Request();
try {
    $response = Router::resolveRoute($request);
    echo $response->send();
} catch (AppError $e) {
    echo $e->getMessage();
}
