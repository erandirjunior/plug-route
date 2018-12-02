<?php

namespace PlugRoute;

use PlugRoute\Rules\Routes\ManagerRoute;
use PlugRoute\Rules\Routes\RouteService;

class PlugRoute
{
    private $routeService;

    private $route;

    public function __construct()
	{
		$this->routeService = new RouteService();
	}

	public function get(string $route, $callback)
    {
    	$this->route = $this->routeService->addRoute('GET', $route, $callback);
    	return $this;
    }

    public function post(string $route, $callback)
    {
		$this->route = $this->routeService->addRoute('POST', $route, $callback);
		return $this;
    }

    public function put(string $route, $callback)
    {
		$this->route = $this->routeService->addRoute('PUT', $route, $callback);
		return $this;
    }

    public function delete(string $route, $callback)
    {
		$this->route = $this->routeService->addRoute('DELETE', $route, $callback);
		return $this;
    }

    public function patch(string $route, $callback)
    {
		$this->route = $this->routeService->addRoute('PATCH', $route, $callback);
		return $this;
    }

    public function group(string $route, callable $callback)
    {
		$this->route = $this->routeService->addGroup($this, $route, $callback);
		return $this;
    }

    public function any(string $route, $callback)
    {
		$this->route = $this->routeService->addRouteTypeAny($route, $callback);
		return $this;
    }

    public function name(string $name)
	{
		$this->routeService->addName($name, $this->route);
	}

    public function on()
    {
		(new ManagerRoute($this->routeService->getRoutes()))->manipulateRoutes();
    }
}