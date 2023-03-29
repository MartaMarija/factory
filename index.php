<?php

require_once 'vendor/autoload.php';

use App\v1\Request;
use App\v1\Router;
use App\v1\Response;

include("./src/v1/routes.php");

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
if ($method != "POST" && $method != "GET") {
    echo "Metoda '" . $method . "' nije podržana";
    return;
}

$request = new Request(apache_request_headers(), $_GET, $_POST);

$router = Router::getRouter();

$response = new Response($router->resolveRoute($method, $url, $request));
echo $response->send();




