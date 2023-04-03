<?php

namespace App;

class Router
{
    private static array $routes;

    public static function addRoute(string $method, string $url, callable $callback): void
    {
        $urlParts = explode('/', $url);
        $routeData = ['method' => $method, 'urlParts' => $urlParts, 'callback' => $callback];
        self::$routes[] = $routeData;
    }

    public static function resolveRoute(RequestInterface $request): ResponseInterface
    {
        $requestUrlParts = $request->getUrlParts();
        $requestMethod = $request->getMethod();
        foreach (self::$routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            $routeUrlParts = $route['urlParts'];
            if (count($routeUrlParts) !== count($requestUrlParts)) {
                continue;
            }
            $routeExists = true;
            foreach ($routeUrlParts as $index => $routeUrlPart) {
                if ($routeUrlPart !== $requestUrlParts[$index] && !preg_match('/^{.*}$/', $routeUrlPart)) {
                    $routeExists = false;
                    break;
                }
                if (preg_match('/^{.*}$/', $routeUrlPart)) {
                    $key = str_replace(['{', '}'], '', $routeUrlPart);
                    $value = $requestUrlParts[$index];
                    $request->addParam($key, $value);
                }
            }
            if ($routeExists) {
                return call_user_func($route['callback'], $request);
            }
        }
        throw new AppError(Response::HTTP_NOT_FOUND, 'Route not found!');
    }
}
