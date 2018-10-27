<?php

namespace PlugRoute\Services;

use PlugRoute\Helpers\RouteHelper;
use PlugRoute\PlugRoute;

class RouteService
{
    private $routes;

    public function __construct()
    {
        foreach (['GET', 'POST', 'PUT', 'DELETE', 'PATCH'] as $type) {
            $this->routes[$type] = [];
        }
    }

    public function addRoutes($route, $callback, $typeRequest)
    {
        $this->routes[$typeRequest][] = ['route' => $route, 'callback' => $callback];
    }

    public function manipulateRouteTypeAny($route, $callback)
    {
        foreach ($this->routes as $typeRequest => $routes) {
            $this->routes[$typeRequest][] = ['route' => $route, 'callback' => $callback];
        }
    }

    public function addRouteGroup($routeBase, $callback, PlugRoute $plugRoute)
    {
        $routesBeforeGroup = $this->routes;
        $callback($plugRoute);
        $this->manipulateRouteGroup($routeBase, $routesBeforeGroup);
    }

    private function manipulateRouteGroup($routeBase, $routesBeforeGroup)
    {
        array_walk($this->routes, function ($routes, $typeRoute) use ($routeBase, $routesBeforeGroup) {
            foreach ($routes as $index => $route) {
                if (empty($routesBeforeGroup[$typeRoute][$index])) {
                    $this->routes[$typeRoute][$index]['route'] = RouteHelper::pathRoute($routeBase, $route['route']);
                }
            }
        });
    }

    public function getRoutes()
    {
        return $this->routes;
    }
}