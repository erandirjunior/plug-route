<?php

namespace PlugRoute;

use PlugRoute\Routes\ManagerRoute;

/**
 * @method post(string $route, $callback)
 * @method get(string $route, $callback)
 * @method put(string $route, $callback)
 * @method delete(string $route, $callback)
 * @method patch(string $route, $callback)
 *
 * Class PlugRoute
 * @package PlugRoute
 */
class PlugRoute
{
    private $routes;

    private $index;

    private $typeMethod;

    private $name;

    private $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

    private $prefix;

    public function __construct()
	{
		$this->name = null;
		$this->routes = [
			'GET' => [],
			'POST' => [],
			'PUT' => [],
			'DELETE' => [],
			'PATCH' => []
		];
	}

	public function __call(string $name, $callback)
	{
		$nameMethod = strtoupper($name);

		if (!in_array($nameMethod, $this->methods)) {
			throw new \Exception("Method don't exists");
		}

		$this->addRoutes($nameMethod, $callback);
		return $this;
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
			return $this;
		}
	}

	public function group(string $route, $callback)
	{
		$this->prefix = $route;
		$callback($this);
		$this->prefix = '';
		return $this;
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

	public function any(string $route, $callback)
	{
		foreach ($this->methods as $typeRequest => $routes) {
			$this->addRoutes($route, $callback);
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
//        var_dump($this->routes);die;
		(new ManagerRoute($this->routes))->manipulateRoutes();
    }
}