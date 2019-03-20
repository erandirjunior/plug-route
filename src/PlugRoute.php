<?php

namespace PlugRoute;

class PlugRoute
{
    private $routes;

    private $routeError;

    private $prefix;

    private $name;

    private $middleware;

    private $index;

    private $typeMethod;

    private $methods;

    public function __construct()
	{
		$this->routes = [
			'GET' 		=> [],
			'POST' 		=> [],
			'PUT' 		=> [],
			'DELETE' 	=> [],
			'PATCH' 	=> []
		];
		$this->routeError 	= [];
		$this->prefix 		= '';
		$this->methods		= ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
		$this->middleware	= [];
	}

	public function setRouteError($callback)
	{
		$this->routeError = ['callback' => $callback];
	}

	public function getRoutes()
	{
		return $this->routes;
	}

	public function get(string $route, $callback)
    {
        $this->addRoutes('GET', [$this->prefix.$route, $callback]);
        return $this;
    }

	public function post(string $route, $callback)
    {
        $this->addRoutes('POST', [$route, $callback]);
        return $this;
    }

	public function put(string $route, $callback)
    {
        $this->addRoutes('PUT', [$route, $callback]);
        return $this;
    }

	public function delete(string $route, $callback)
    {
        $this->addRoutes('DELETE', [$route, $callback]);
        return $this;
    }

	public function patch(string $route, $callback)
    {
        $this->addRoutes('PATCH', [$route, $callback]);
        return $this;
    }

    public function any(string $route, $callback)
    {
        foreach ($this->methods as $typeRequest) {
            $this->addRoutes($typeRequest, [$route, $callback]);
        }
    }

    public function group(array $route, $callback)
	{
	    $this->prefixExists($route);
        $this->middlewareExists($route);

		$callback($this);

		$this->prefix       = '';
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
            $this->prefix = $route['prefix'];
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
        $exists = false;

        if ($this->routes[$typeRequest]) {
            $exists = $this->removeDuplicateRoutes($typeRequest, $callback);
        }

        if (!$exists) {
            $this->routes[$typeRequest][] = [
                'route' 	    => $callback[0],
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
		echo (new RouteProcessor($this->routes, $this->routeError))->run();
    }
}
