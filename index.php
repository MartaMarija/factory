<?php

require_once 'vendor/autoload.php';

use App\Request;
use App\Router;

include("./src/routes.php");

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
if ($method != "POST" && $method != "GET") {
    echo "Metoda '" . $method . "' nije podržana";
    return;
}


$request = new Request(apache_request_headers(), $_GET, $_POST);

$router = Router::getRouter();
$response = $router->resolveRoute($method, $url, $request);
echo $response;




