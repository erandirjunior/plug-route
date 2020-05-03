<?php

namespace PlugRoute;

use \PlugRoute\Http\Request;

class PlugRoute
{
	private $route;

	private $request;

    public function __construct(RouteContainer $route, Request $request)
	{
		$this->route 	= $route;
		$this->request 	= $request;
	}

	public function getNotFound()
	{
		return $this->route->getErrorRouteNotFound();
	}

	public function notFound($callback)
	{
		$this->route->setErrorRouteNotFound($callback);
	}
	
	public function getNamedRoute()
	{
		return $this->route->getNamedRoute();
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

    public function middleware(array $middleware)
	{
		$this->route->setMiddleware($middleware);
        return $this;
	}

	public function namespace(string $namespace, callable $callback)
	{
		$this->route->addGroup($this, ['namespace' => $namespace], $callback);
	}

	public function redirect($from, $to, $code = 301)
    {
        $this->route->addRoute('GET', $from, function () use ($to, $code) {
            $this->request->redirect($to, $code);
        });
    }

	public function loadFromJson($json)
	{
		$this->route->loadRoutesFromJson($json);
    }

	public function loadFromXML($xml)
	{
		$this->route->loadRoutesFromXML($xml);
    }

    private function addNamedRoute()
	{
		$this->request->setRouteNamed($this->getNamedRoute());
	}

	public function on(array $dependencies = [])
	{
		$this->addNamedRoute();

		$simpleRoute 	= new SimpleRoute();
		$dynamicRoute	= new DynamicRoute();

		$manager = new RouteManager($this->route, $this->request, $simpleRoute, $dynamicRoute);

		echo $manager->run($dependencies);
	}
}
