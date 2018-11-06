<?php

namespace PlugRoute;

use PlugRoute\Helpers\RouteHelper;
use PlugRoute\Services\Routes\RouteService;

class PlugRoute
{
    private $routes = [
        'GET'       => [],
        'POST'      => [],
        'PUT'       => [],
        'PATCH'     => [],
        'DELETE'    => []
	];

    public function get(string $route, $callback)
    {
        $this->routes['GET'][] = [
            'route' => $route,
            'callback' => $callback
        ];
    }

    public function post(string $route, $callback)
    {
        $this->routes['POST'][] = [
            'route' => $route,
            'callback' => $callback
        ];
    }

    public function put(string $route, $callback)
    {
        $this->routes['PUT'][] = [
            'route' => $route,
            'callback' => $callback
        ];
    }

    public function delete(string $route, $callback)
    {
        $this->routes['DELETE'][] = [
            'route' => $route,
            'callback' => $callback
        ];
    }

    public function patch(string $route, $callback)
    {
        $this->routes['PATCH'][] = [
            'route' => $route,
            'callback' => $callback
        ];
    }

    public function group(string $route, callable $callback)
    {
        $routesBeforeGroup = $this->routes;
        $callback($this);
        $this->addRouteGroup($route, $routesBeforeGroup);
    }

    public function any(string $route, $callback)
    {
        foreach ($this->routes as $typeRequest => $routes) {
            $this->routes[$typeRequest][] = ['route' => $route, 'callback' => $callback];
        }
    }

    private function addRouteGroup($routeBase, $routesBeforeGroup)
    {
        array_walk($this->routes, function ($routes, $typeRoute) use ($routeBase, $routesBeforeGroup) {
            foreach ($routes as $index => $route) {
                if (empty($routesBeforeGroup[$typeRoute][$index])) {
                    $this->routes[$typeRoute][$index]['route'] = RouteHelper::pathRoute($routeBase, $route['route']);
                }
            }
        });
    }

    public function on()
    {
		(new RouteService($this->routes))->manipulateRoutes();
    }
}