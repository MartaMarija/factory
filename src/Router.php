<?php

namespace App;

class Router
{
    private static array $routes;

    public static function addRoute(string $method, string $url, callable $callback): void
    {
        $urlParts = explode('/', $url);
        $routeData = ['method' => $method, 'url' => $urlParts, 'callback' => $callback];
        self::$routes[] = $routeData;
    }

    public static function resolveRoute(RequestInterface $request): ResponseInterface
    {
        $requestUrl = $request->getUrl();
        $method = $request->getMethod();
        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $routeUrl=$route['url'];
            if (count($routeUrl) !== count($requestUrl)) {
                continue;
            }
            //Ako su metoda i broj "dijelova URL-a" jednaki,
            //uspoređuju se dijelovi URL-a requesta i routa
            $urlExists = true;
            for ($urlPart = 0; $urlPart < count($requestUrl); $urlPart++) {
                if ($routeUrl[$urlPart] !== $requestUrl[$urlPart]) {
                    //Ako dijelovi nisu jednaki, provjerava se da nije riječ o {} dijelu
                    if (!preg_match('/^{.*}$/', $routeUrl[$urlPart])) {
                        $urlExists = false;
                        break;
                    } else {
                        //Ako je riječ o {} dijelu, onda se taj dio doda kao još jedan parametar
                        $key = str_replace(['{', '}'], '', $routeUrl[$urlPart]);
                        $request->addParam($key, $requestUrl[$urlPart]);
                    }
                }
            }
            if ($urlExists) {
                return call_user_func($route['callback'], $request);
            }
        }
        throw new AppError(404, "Route not found!");
    }
}
