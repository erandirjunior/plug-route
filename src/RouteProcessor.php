<?php

namespace PlugRoute;

use PlugRoute\Callback\Callback;
use PlugRoute\Helpers\PlugHelper;
use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Helpers\ValidateHelper;

class RouteProcessor
{
	private $callback;
	private $urlParameters;
	private $url = '';

	private $routes;

	public function __construct($routes)
	{
		$this->callback      	= new Callback($this->getNamedRoutes($routes));
		$this->urlParameters 	= [];
		$this->url           	= RequestHelper::getUrlPath();
		$requestType 			= RequestHelper::getTypeRequest();
		$this->routes        	= $requestType !== 'OPTIONS' ? $routes[$requestType] : [];
	}

	public function run()
	{
		try {
			foreach ($this->routes as $route) {
				if (ValidateHelper::isEqual($this->handleRoute($route['route'], $this->url), $this->url)) {
					return $this->callback->handleCallback($route, $this->urlParameters);
				}

				$this->urlParameters = [];
			}

			throw new \Exception("Error: Route wasn't defined");
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

	private function handleRoute($route, $urlPath)
	{
		$match          = PlugHelper::getMatch($route);
		$routeArray     = explode('/', $route);
		$urlArray       = explode('/', $urlPath);
		$sizeUrlArray   = count($routeArray);
		$sizeRouteArray = count($urlArray);
		return $sizeRouteArray === $sizeUrlArray ? $this->mountUrlPath($routeArray, $urlArray, $match) : $route;
	}

	private function mountUrlPath($route, $url, $match)
	{
		foreach ($route as $k => $v) {
			if (in_array($v, $match)) {
				$route[$k]             = $url[$k];
				$key = PlugHelper::removeCaracterKey($v);
				$this->urlParameters[$key] = $url[$k];
			}
		}

		return implode('/', $route);
	}

	private function getNamedRoutes($routes)
	{
		$array = [];
		foreach ($routes as $route) {
			foreach ($route as $value) {
				if (!empty($value['name'])) {
					$array[$value['name']] = $value['route'];
				}
			}
		}
		return $array;
	}
}