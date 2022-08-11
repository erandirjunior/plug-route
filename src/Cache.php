<?php

namespace PlugRoute;

class Cache
{
    private static array $routes;

    public static function set(Route $route): void
    {
        $name = $route->getName();
        $path = $route->getRoute();
        self::$routes[$name] = $path;
    }

    public static function get(string $name): string
    {
        return self::$routes[$name] ?? '';
    }
}