<?php

namespace PlugRoute;

class PlugRoute
{
	private $route;

    private $routeError;

    public function __construct()
	{
		$this->route = new Route();
		$this->routeError 	= [];
	}

	public function setRouteError($callback)
	{
		$this->route->setRouteError($callback);
	}

	public function getRoutes()
	{
		return $this->route->getRoutes();
	}

	private function addRoute($type, $route, $callback)
	{
		$this->route->addRoute($type, $route, $callback);
		return $this;
	}

	public function get(string $route, $callback)
    {
        return $this->addRoute('GET', $route, $callback);
    }

	public function post(string $route, $callback)
    {
        return $this->addRoute('POST', $route, $callback);
    }

	public function put(string $route, $callback)
    {
        return $this->addRoute('PUT', $route, $callback);
    }

	public function delete(string $route, $callback)
    {
        return $this->addRoute('DELETE', $route, $callback);
    }

	public function patch(string $route, $callback)
    {
        return $this->addRoute('PATCH', $route, $callback);
    }

	public function options(string $route, $callback)
	{
		return $this->addRoute('OPTIONS', $route, $callback);
	}

	public function match(array $types, string $route, $callback)
	{
		$this->route->addMultipleRoutes($types, $route, $callback);
    }

    public function any(string $route, $callback)
    {
		$this->route->addMultipleRoutes([], $route, $callback);
	}

    public function group(array $route, callable $callback)
	{
	    $this->route->addGroup($this, $route, $callback);
	}

    public function name(string $name)
	{
		$this->route->setName($name);
        return $this;
	}

    public function middleware($middleware)
	{
		$this->route->setMiddleware($middleware);
        return $this;
	}

	public function on()
	{
		(new RouteProcessor($this->route))->run();
	}
}
