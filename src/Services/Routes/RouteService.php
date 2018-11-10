<?php

namespace PlugRoute\Services\Routes;

use PlugRoute\PlugRoute;

class RouteService
{
	private $routes;

	private $preName = '';

	private $exists = false;

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

	public function addRoute($typeRequest, $route, $callback)
	{
		$path = $this->preName.$route;

		$this->exists = false;

		foreach ($this->routes[$typeRequest] as $k => $v) {
			if ($v['route'] === $path) {
				$this->routes[$typeRequest][$k] = [
					'route' => $path,
					'callback' => $callback
				];
				$this->exists = true;
			}
		}
		$this->add($typeRequest, $path, $callback);
	}

	public function add($typeRequest, $path, $callback)
	{
		if (!$this->exists) {
			$this->routes[$typeRequest][] = [
				'route' => $path,
				'callback' => $callback
			];
		}
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
			$this->routes[$typeRequest][] = ['route' => $route, 'callback' => $callback];
		}
	}
}