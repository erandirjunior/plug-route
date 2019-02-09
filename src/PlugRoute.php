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
        return $this->addRoutes('GET', $this->prefix.$route, $callback);
    }

	public function post(string $route, $callback)
    {
        return $this->addRoutes('POST', $this->prefix.$route, $callback);
    }

	public function put(string $route, $callback)
    {
        return $this->addRoutes('PUT', $this->prefix.$route, $callback);
    }

	public function delete(string $route, $callback)
    {
        return $this->addRoutes('DELETE', $this->prefix.$route, $callback);
    }

	public function patch(string $route, $callback)
    {
        return $this->addRoutes('PATCH', $this->prefix.$route, $callback);
    }

    public function any(string $route, $callback)
    {
        foreach ($this->methods as $typeRequest) {
            $this->addRoutes($typeRequest, $this->prefix.$route, $callback);
        }
    }

    public function group(array $route, $callback)
	{
	    $this->addPrefixIfExist($route);
        $this->addMiddlewareIfExist($route);

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

    private function addPrefixIfExist($route)
    {
		$this->prefix = !empty($route['prefix']) ? $route['prefix'] : '';
	}

    private function addMiddlewareIfExist($route)
    {
		$this->middleware = !empty($route['middleware']) && is_array($route['middleware']) ? $route['middleware'] : '';
	}

    private function addRoutes(string $typeRequest, string $route, $callback)
    {
        $exists = false;

        if ($this->routes[$typeRequest]) {
            $exists = $this->removeDuplicateRoutes($typeRequest, $route, $callback);
        }

        if (!$exists) {
            $this->routes[$typeRequest][] = [
                'route' 	    => $route,
                'callback' 	    => $callback,
                'name'		    => $this->name,
                'middleware'	=> [],
            ];
            $this->setLastRoute($typeRequest, $this->getIndex($typeRequest));
            $this->addMiddleware();
        }
        return $this;
    }

    private function addMiddleware()
    {
    	if (is_array($this->middleware)) {
			foreach ($this->middleware as $middleware) {
				$this->middleware($middleware);
			}
		}
    }

	private function removeDuplicateRoutes($typeRequest, $route, $callback)
	{
		$exists = false;
        foreach ($this->routes[$typeRequest] as $k => $v) {
			if ($v['route'] === $route) {
				$this->routes[$typeRequest][$k] = [
					'route' 	=> $route,
					'callback' 	=> $callback,
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
		(new RouteProcessor($this->routes, $this->routeError))->run();
    }
}
