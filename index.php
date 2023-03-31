<?php

require_once 'vendor/autoload.php';

use App\Router;
use App\Request;

include("./src/routes.php");

$request = new Request();
$response = Router::resolveRoute($request);
echo $response->send();




