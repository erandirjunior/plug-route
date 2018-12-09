<?php

namespace PlugRoute;

use PlugRoute\Rules\Routes\ManagerRoute;

class PlugRoute
{
    private $routes;

    private $index;

    private $typeMethod;

    private $settings;

    private $name;

    private $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

    public function __construct()
	{
		$this->name = null;
		$this->routes = [];
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
				'route' 	=> $callback[0],
				'callback' 	=> $callback[1],
				'name'		=> $this->name
			];
			$this->setLastRoute($typeRequest, $this->getIndex($typeRequest));
			return $this;
		}
	}

	public function name(string $name)
	{
		$this->routes[$this->typeMethod][$this->index]['name'] = $name;
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
		(new ManagerRoute($this->routes))->manipulateRoutes();
    }
}