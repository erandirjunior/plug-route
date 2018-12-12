<?php

namespace PlugRoute;

use PlugRoute\Routes\ManagerRoute;

class PlugRoute
{
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => []
    ];

    private $prefix;

    private $name;

    private $middleware = [];

    private $index;

    private $typeMethod;

    private $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

	public function get(string $name, $callback)
    {
        $this->addRoutes('GET', [$name, $callback]);
        return $this;
    }

	public function post(string $name, $callback)
    {
        $this->addRoutes('POST', [$name, $callback]);
        return $this;
    }

	public function put(string $name, $callback)
    {
        $this->addRoutes('PUT', [$name, $callback]);
        return $this;
    }

	public function delete(string $name, $callback)
    {
        $this->addRoutes('DELETE', [$name, $callback]);
        return $this;
    }

	public function patch(string $name, $callback)
    {
        $this->addRoutes('PATCH', [$name, $callback]);
        return $this;
    }

    public function any(string $route, $callback)
    {
        foreach ($this->methods as $typeRequest => $routes) {
            $this->addRoutes($route, $callback);
        }
    }

    public function group(array $route, $callback)
	{
	    $this->prefixExists($route);
        $this->middlewareExists($route);

		$callback($this);

		$this->prefix       = null;
		$this->middleware   = [];
	}

    public function name(string $name)
	{
		$this->routes[$this->typeMethod][$this->index]['name'] = $name;
        return $this;
	}

    public function middleware($middleware)
	{
        $this->routes[$this->typeMethod][$this->index]['middleware'][] = $middleware;
        return $this;
	}

    private function prefixExists($route)
    {
        if (!empty($route['prefix'])) {
            $this->prefix = $route;
        }
	}

    private function middlewareExists($route)
    {
        if (!empty($route['middleware']) && is_array($route['middleware'])) {
            $this->middleware = $route['middleware'];
        }
	}

    private function addRoutes(string $typeRequest, array $callback)
    {
        $exists = $this->removeDuplicateRoutes($typeRequest, $callback);
        if (!$exists) {
            $this->routes[$typeRequest][] = [
                'route' 	    => $this->prefix.$callback[0],
                'callback' 	    => $callback[1],
                'name'		    => $this->name,
                'middleware'	=> [],
            ];
            $this->setLastRoute($typeRequest, $this->getIndex($typeRequest));
            $this->addMiddleware();
        }
    }

    private function addMiddleware()
    {
        foreach ($this->middleware as $middleware) {
            $this->middleware($middleware);
        }
    }

	private function removeDuplicateRoutes($typeRequest, $callback)
	{
		$exists = false;
		foreach ($this->routes[$typeRequest] as $k => $v) {
			if ($v['route'] === $callback[0]) {
				$this->routes[$typeRequest][$k] = [
					'route' 	=> $callback[0],
					'callback' 	=> $callback[1],
					'name' 		=> null
				];
				$exists 			= true;
				$this->setLastRoute($typeRequest, $k);
			}
		}
		return $exists;
	}

	private function getIndex($typeMethod)
	{
		return count($this->routes[$typeMethod]) - 1;
	}

	private function setLastRoute($typeMethod, $index)
	{
		$this->index 		= $index;
		$this->typeMethod 	= $typeMethod;
	}

    public function on()
    {
		(new ManagerRoute($this->routes))->manipulateRoutes();
    }
}