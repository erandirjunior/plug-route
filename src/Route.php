<?php

namespace PlugRoute;

class Route
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
		$this->routes = [
			'GET' 		=> [],
			'POST' 		=> [],
			'PUT' 		=> [],
			'DELETE' 	=> [],
			'PATCH' 	=> [],
			'OPTIONS' 	=> []
		];
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
			'middleware'	=> [],
		];
	}

	public function getErrorRoute()
	{
		return $this->routeError;
	}

	public function setRouteError($callback)
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
			$this->routes[$this->typeMethod][$this->index]['middleware'][] = $middleware;
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
		if (!empty($route['middleware'])) {
			foreach ($route['middleware'] as $middleware) {
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

	public function addMultipleRoutes(array $types = [], string $route, $callback)
	{
		if (!$types) {
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
			'middleware'	=> [],
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
		if (!empty($route['middleware'])) {
			foreach ($route['middleware'] as $middleware) {
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
}