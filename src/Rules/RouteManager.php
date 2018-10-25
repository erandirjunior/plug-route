<?php

namespace PlugRoute\Rules;

class RouteManager
{
	private $routes;

	public function __construct()
	{
		$this->routes = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
	}

	public function addRoutes($route, $callback, $typeRequest)
	{
		$this->routes[$typeRequest][] = [
			'route' => $route,
			'callback' => $callback
		];
	}

	// TODO Inserir todas as rotas do tipo any em cada tipo de rota
	public function manipulateRouteTypeAny()
	{

	}

	// TODO Verificar funcionamento do método
	public function addRouteGroup($route, $callback)
	{
		$routesBeforeGroup = $this->routes;
		$callback($this);

		foreach ( $this->routes as $key => $value) {
			if (empty($routesBeforeGroup[$key])) {
				$this->routes[$key]['route'] = RouteHelper::pathRoute($route, $value['route']);
			}
		}

		return $this->routes;
	}

	public function getRoutes()
	{
		return $this->routes;
	}
}