<?php

namespace App;

class Router
{
    private static array $routes;

    public static function addRoute(string $method, string $url, callable $callback): void
    {
        $routeData = ['method' => $method, 'url' => $url, 'callback' => $callback];
        self::$routes[] = $routeData;
    }

    public static function resolveRoute(RequestInterface $request): ResponseInterface
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        foreach (self::$routes as $route) {
            if ($route['method'] === $method && $route['url'] === $url) {
                return call_user_func($route['callback'], $request);
            }
        }
        return new Response("Ruta: '" . $url . "' + Metoda: '" . $method . "' ne postoji!");
    }
}
