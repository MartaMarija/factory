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
    
    public function addRoute($method, $url, $callback): void
    {
        $route_data = array("method" => $method, "url" => $url, "callback" => $callback);
        $this->routes[] = $route_data;
    }
    
    public function resolveRoute($method, $url, RequestInterface $request)
    {
        foreach ($this->routes as $route) {
            if ($route["method"] === $method && $route["url"] === $url) {
                return call_user_func($route["callback"], $request);
            }
        }
        return "Ruta: '" . $url . "' + Metoda: '" . $method . "' ne postoji!";
    }
}

