<?php

namespace PlugRoute\Rules\Routes;

use PlugRoute\PlugRoute;

class RouteService
{
	private $routes;

	private $preName = '';

	private $index;

	public function __construct()
	{
		$this->routes = [
			'GET' => [],
			'POST' => [],
			'PUT' => [],
			'DELETE' => [],
			'PATCH' => []
		];
	}

	public function getRoutes(): array
	{
		return $this->routes;
	}

	private function add($typeRequest, $path, $callback)
	{
		if (!is_array($this->index)) {
			$this->routes[$typeRequest][] = [
				'route' => $path,
				'callback' => $callback,
				'name'	=> null,
			];
			$this->index = [$typeRequest, count($this->routes[$typeRequest]) - 1];
		}

		return $this->index;
	}

	public function addRoute($typeRequest, $route, $callback)
	{
		$path = $this->preName.$route;
		$this->index = null;

		if (count($this->routes[$typeRequest]) > 0) {
			$this->removeDuplicateRoutes($typeRequest, $callback, $path);
		}

		return $this->add($typeRequest, $path, $callback);
	}

	public function addName($name, array $index)
	{
		$typeRquest = $index[0];
		$index 		= $index[1];
		$this->routes[$typeRquest][$index]['name'] = $name;

	}

	public function addGroup(PlugRoute $plugRoute, $route, $callback)
	{
		$this->preName = $route;
		$callback($plugRoute);
		$this->preName = '';
	}

	public function addRouteTypeAny(string $route, $callback)
	{
		foreach ($this->routes as $typeRequest => $routes) {
			$this->routes[$typeRequest][] = [
				'route' => $route,
				'callback' => $callback,
				'name'	=> null
			];
		}
	}

	private function removeDuplicateRoutes($typeRequest, $callback, $path)
	{
		foreach ($this->routes[$typeRequest] as $k => $v) {
			if ($v['route'] === $path) {
				$this->routes[$typeRequest][$k] = [
					'route' => $path,
					'callback' => $callback
				];
				$this->index = [$typeRequest, $k];
			}
		}
	}
}