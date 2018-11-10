<?php

namespace PlugRoute;

use PlugRoute\Helpers\RouteHelper;
use PlugRoute\Services\Routes\ManagerRouteService;
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

    private $routeService;

    public function __construct()
	{
		$this->routeService = new RouteService();
	}

	public function get(string $route, $callback)
    {
    	$this->routeService->addRoute('GET', $route, $callback);
    }

    public function post(string $route, $callback)
    {
		$this->routeService->addRoute('POST', $route, $callback);
    }

    public function put(string $route, $callback)
    {
		$this->routeService->addRoute('PUT', $route, $callback);
        $this->routes['PUT'][] = [
            'route' => $route,
            'callback' => $callback
        ];
    }

    public function delete(string $route, $callback)
    {
		$this->routeService->addRoute('DELETE', $route, $callback);
    }

    public function patch(string $route, $callback)
    {
		$this->routeService->addRoute('PATCH', $route, $callback);
    }

    public function group(string $route, callable $callback)
    {
    	$this->routeService->addGroup($this, $route, $callback);
    }

    public function any(string $route, $callback)
    {
    	$this->routeService->addRouteTypeAny($route, $callback);
        foreach ($this->routes as $typeRequest => $routes) {
            $this->routes[$typeRequest][] = ['route' => $route, 'callback' => $callback];
        }
    }

    public function on()
    {
		(new ManagerRouteService($this->routeService->getRoutes()))->manipulateRoutes();
    }
}