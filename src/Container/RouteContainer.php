<?php

namespace PlugRoute\Container;

use PlugRoute\Route;
use PlugRoute\RouteType;

class RouteContainer
{
    private array $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    public function addRoute(Route $route, string $type)
    {
        $this->routes[$type][] = $route;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getRoutesByType(string $type): array
    {
        $type = strtolower($type);
        return $this->routes[$type] ?? [];
    }

    public function getFallbackRoute(): array
    {
        return $this->routes[RouteType::FALLBACK] ?? [];
    }
}