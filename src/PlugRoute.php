<?php

namespace PlugRoute;


use PlugRoute\Helpers\RouteHelper;
use PlugRoute\Services\RouteService;

class PlugRoute
{
    private $manager;

    public function __construct()
	{
		$this->manager = new RouteService();
	}

    public function get(string $route, $callback)
    {
    	$this->manager->addRoutes($route, $callback, 'GET');
    }

	public function post(string $route, $callback)
	{
		$this->manager->addRoutes($route, $callback, 'POST');
	}

	public function put(string $route, $callback)
	{
		$this->manager->addRoutes($route, $callback, 'PUT');
	}

	public function delete(string $route, $callback)
	{
		$this->manager->addRoutes($route, $callback, 'DELETE');
	}

	public function patch(string $route, $callback)
	{
		$this->manager->addRoutes($route, $callback, 'PATCH');
	}

    public function group(string $route, callable $callback)
    {
		$this->manager->addRouteGroup($route, $callback, $this);
    }

    public function any(string $route, $callback)
    {
		$this->manager->manipulateRouteTypeAny($route, $callback, $this);
    }

    public function getRoutes()
    {
        return $this->manager->getRoutes();
    }

    public function on()
    {
        $config = new PlugConfig($this->getRoutes());
        return $config->main();
    }
}