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
		$this->routes[$typeRequest][] = [
			'route' => $route,
			'callback' => $callback
		];
	}

	public function manipulateRouteTypeAny($route, $callback)
	{
        foreach ($this->routes as $typeRequest => $routes) {
            $this->routes[$typeRequest][] = [
                'route' => $route,
                'callback' => $callback
            ];
        }
	}

	public function addRouteGroup($routeBase, $callback, PlugRoute $plugRoute)
	{
		$routesBeforeGroup = $this->routes;
		$callback($plugRoute);
		$this->manipulateRouteGroup($routeBase, $routesBeforeGroup);
	}

    /**
     * Mount routes that are grouped.
     *
     * @param $route
     * @param callable $callback
     * @return array
     */
    private function manipulateRouteGroup($routeBase, $routesBeforeGroup)
    {
        foreach ($this->routes as $typeRoutes => $values) {
            foreach ($values as $index => $routes) {
                if (empty($routesBeforeGroup[$typeRoutes][$index])) {
                    $this->routes[$typeRoutes][$index]['route'] = RouteHelper::pathRoute($routeBase, $routes['route']);
                }
            }
        }
    }

	public function getRoutes()
	{
		return $this->routes;
	}
}