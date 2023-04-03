<?php

require_once 'vendor/autoload.php';

use App\AppError;
use App\Router;
use App\Request;

include("./src/routes.php");

$request = new Request();
try {
    $response = Router::resolveRoute($request);
} catch(AppError $e) {
    echo $e->getMessage();
}
echo $response->send();
