<?php

namespace App;

class Router
{
    private array $routes;
    private static mixed $router;

    public static function getRouter()
    {
        $cls = static::class;
        if (!isset(self::$router[$cls])) {
            self::$router[$cls] = new static();
        }
        return self::$router[$cls];
    }

    public function addRoute(string $method, string $url, callable $callback): void
    {
        $routeData = ['method' => $method, 'url' => $url, 'callback' => $callback];
        $this->routes[] = $routeData;
    }

    public function resolveRoute(RequestInterface $request): ResponseInterface
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['url'] === $url) {
                return call_user_func($route['callback'], $request);
            }
        }
        return new Response("Ruta: '" . $url . "' + Metoda: '" . $method . "' ne postoji!");
    }
}
