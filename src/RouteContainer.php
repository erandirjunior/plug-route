<?php

namespace PlugRoute;

class RouteContainer
{
	private $routes;

	private $routeError;

	private $prefix;

	private $name;

	private $namespace;

	private $middleware;

	private $index;

	private $typeMethod;

	public function __construct()
	{
	    $keys = ['GET' , 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'];

	    foreach ($keys as $value) {
	        $this->routes[$value] = [];
        }

		$this->middleware = [];
	}

	public function getRoutes()
	{
		return $this->routes;
	}

	private function setRoutes(string $requestType, string $route, $callback)
	{
		$this->routes[$requestType][] = [
			'route' 	    => $route,
			'callback' 	    => is_string($callback) ? $this->namespace.$callback : $callback,
			'name'		    => $this->name,
			'middlewares'	=> [],
		];
	}

	public function getErrorRouteNotFound()
	{
		return $this->routeError;
	}

	public function setErrorRouteNotFound($callback)
	{
		$this->routeError = ['callback' => $callback];
	}

	public function setName($name)
	{
		$this->routes[$this->typeMethod][$this->index]['name'] = $name;
	}

	public function setMiddleware(array $middlewares)
	{
		foreach ($middlewares as $middleware) {
			$this->routes[$this->typeMethod][$this->index]['middlewares'][] = $middleware;
		}
	}

	public function addGroup($plugRoute, array $route, $callback)
	{
		$this->beforeGroup($route);
		$callback($plugRoute);
		$this->afterGroup($route);
	}

	private function cachePrefixIfExists($route)
	{
		if (!empty($route['prefix'])) {
			$this->prefix .= $route['prefix'];
		}
	}

	private function cacheNamespaceIfExists($route)
	{
		if (!empty($route['namespace'])) {
			$this->namespace .= $route['namespace'];
		}
	}

	private function cacheMiddlewareIfExists($route)
	{
		if (!empty($route['middlewares'])) {
			foreach ($route['middlewares'] as $middleware) {
				$this->middleware[] = $middleware;
			}
		}
	}

	public function addRoute(string $requestType, string $route, $callback)
	{
		$route = $this->prefix.$route;
		if (!$this->removeDuplicateRoutes($requestType, $route, $callback)) {
			$this->setRoutes($requestType, $route, $callback);
			$this->setLastRoute($requestType, $this->getIndex($requestType));
			$this->setMiddleware($this->middleware);
		}

		return $this;
	}

	private function removeDuplicateRoutes($typeRequest, $route, $callback)
	{
		$exists = false;
		foreach ($this->routes[$typeRequest] as $k => $v) {
			if ($v['route'] === $route) {
				$this->replaceRoute($typeRequest, $route, $callback, $k);
				$this->setLastRoute($typeRequest, $k);
				$exists = true;
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

	public function loadRoutesFromJson($path)
	{
		$content = file_get_contents($path);

		$json = json_decode($content, true);

		foreach ($json['routes'] as $route) {
			if (!empty($route['group'])) {
				$this->handleGroupJsonRoutes($route);

				continue;
			}

			$this->handleSimpleJsonRoutes($route);
		}
	}

	public function loadRoutesFromXML($path)
	{
		$content = simplexml_load_file($path);

		foreach ($content as $routes) {
			if (!empty($routes->group)) {
				$this->handleGroupXMLRoutes($routes);

				continue;
			}

			$this->handleSimpleXMLRoutes($routes);
		}
	}

	public function handleSimpleJsonRoutes($route)
	{
		$this->addRoute($route['method'], $route['path'], $route['callback']);

		$this->setExtrasOfRoute($route);
	}

	public function handleSimpleXMLRoutes($route)
	{
	    $method     = $route->method->__toString();
	    $path       = $route->path->__toString();
	    $callback   = $route->callback->__toString();

		$this->addRoute($method, $path, $callback);

        $extras = [];

        if (!empty($route->name)) {
            $extras['name'] = $route->name->__toString();
        }

        if (!empty($route->middlewares)) {
            foreach ($route->middlewares->middleware as $middleware) {
                $extras['middlewares'][] = $middleware->__toString();
            }
        }

        $this->setExtrasOfRoute($extras);
	}

	public function handleGroupJsonRoutes($route)
	{
		$this->beforeGroup($route['group']);

		foreach ($route['group']['routes'] as $routeGroup) {
			if (isset($routeGroup['group'])) {
				$this->handleGroupJsonRoutes($routeGroup);

				continue;
			}
			
			$this->handleSimpleJsonRoutes($routeGroup);
		}

		$this->afterGroup($route['group']);
	}

	public function handleGroupXMLRoutes($route)
	{
	    $group = $this->mountXMLGroupParameters($route);

		$this->beforeGroup($group);

        foreach ($route->group->route as $routeGroup) {
            if (isset($routeGroup->group)) {
                $this->handleGroupXMLRoutes($routeGroup);

                continue;
            }

            $this->handleSimpleXMLRoutes($routeGroup);
        }

        $this->afterGroup($group);
	}

    private function mountXMLGroupParameters($route)
    {
        $group = [];

        if (!empty($route->group->prefix)) {
            $group['prefix'] = $route->group->prefix->__toString();
        }

        if (!empty($route->group->namespace)) {
            $group['namespace'] = $route->group->namespace->__toString();
        }

        if (!empty($route->group->middlewares)) {
            foreach ($route->group->middlewares->middleware as $middleware) {
                $group['middlewares'][] = $middleware->__toString();
            }
        }

        return $group;
	}

	public function addMultipleRoutes(array $types = [], string $route, $callback)
	{
		if (empty($types)) {
			$types = array_keys($this->routes);
		}

		foreach ($types as $typeRequest) {
			$this->addRoute($typeRequest, $this->prefix.$route, $callback);
		}
	}

	private function replaceRoute($typeRequest, $route, $callback, $k)
	{
		$this->routes[$typeRequest][$k] = [
			'route' 		=> $this->prefix.$route,
			'callback' 		=> is_string($callback) ? $this->namespace.$callback : $callback,
			'name'		    => $this->name,
			'middlewares'	=> [],
		];
	}

	private function beforeGroup(array $route)
	{
		$this->cachePrefixIfExists($route);
		$this->cacheMiddlewareIfExists($route);
		$this->cacheNamespaceIfExists($route);
	}

	private function afterGroup($route)
	{
		$this->removeActualPrefix($route);
		$this->removeActualNamespace($route);
		$this->removeActualMiddleware($route);
	}

	private function removeActualPrefix($route)
	{
		if (!empty($route['prefix'])) {
			$this->prefix = str_replace($route['prefix'], '', $this->prefix);
		}
	}

	private function removeActualNamespace($route)
	{
		if (!empty($route['namespace'])) {
			$this->namespace = str_replace($route['namespace'], '', $this->namespace);
		}
	}

	private function removeActualMiddleware($route)
	{
        if (!empty($route['middlewares'])) {
            foreach ($route['middlewares'] as $middleware) {
				array_pop($this->middleware);
			}
		}
	}

	public function getNamedRoute()
	{
		$array = [];

		foreach ($this->routes as $route) {
			foreach ($route as $value) {
				if (!empty($value['name'])) {
					$array[$value['name']] = $value['route'];
				}
			}
		}

		return $array;
	}

	private function setExtrasOfRoute($extras)
	{
		if (!empty($extras['name'])) {
			$this->setName($extras['name']);
		}

		if (!empty($extras['middlewares'])) {
			$this->setMiddleware($extras['middlewares']);
		}
	}
}