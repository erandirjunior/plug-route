<?php

namespace PlugRoute\Rules\Routes;

use PlugRoute\Exceptions\RouteException;
use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Helpers\RouteHelper;
use PlugRoute\Helpers\ValidateHelper;

class ManagerRoute
{
	private $routes;

	public static $accountUrlNotFound;

	private $urlPath;

	private $simpleRoute;

	private $dynamicRoute;

	public function __construct($routes)
	{
		if (count($routes) === 0) {
			throw new \Exception("You should define at least one route");
		}

		$typeRequest            = RequestHelper::getTypeRequest();
		$this->routes           = $typeRequest !== 'OPTIONS' ? $routes[$typeRequest] : [];
		$this->urlPath          = RequestHelper::getUrlPath();
		$name 					= $this->handleNameRoute();
		$this->simpleRoute      = new SimpleRoute($name);
		$this->dynamicRoute     = new DynamicRoute($name);
	}

	public function manipulateRoutes()
	{
		try {
			array_walk($this->routes, function ($route) {
				$isDynamic = RouteHelper::isDynamic($route['route']);
				$isDynamic ? $this->handleDynamicRoute($route) : $this->handleSimpleRoute($route);
				$this->countError($route);
			});
		} catch (\Exception $e) {
			return $this->showErrorMessage($e->getMessage());
		}
	}

	private function handleDynamicRoute($route)
	{
		$this->dynamicRoute->execute($route, $this->urlPath);
	}

	private function handleSimpleRoute($route)
	{
		return $this->simpleRoute->execute($route, $this->urlPath);
	}

	private function showErrorMessage($message)
	{
		echo $message;
	}

	private function countError()
	{
		if (ValidateHelper::isEqual(count($this->routes), self::$accountUrlNotFound)) {
			throw new RouteException("Error: route don't exist");
		}
	}

	private function handleNameRoute()
	{
		$array = [];
		foreach ($this->routes as $route) {
			if (!empty($route['name'])) {
				$array[$route['name']] = $route['route'];
			}
		}

		return $array;
	}
}