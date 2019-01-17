<?php

namespace PlugRoute;

use PlugRoute\Callback\Callback;
use PlugRoute\Helpers\PlugHelper;
use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Http\Data\DataServer;

class RouteProcessor
{
	use DataServer;

	private $callback;

	private $urlParameters;

	private $url;

	private $routes;

	private $routeError;

	public function __construct(array $routes, array $routeError = [])
	{
		$this->callback      	= new Callback($this->getNamedRoutes($routes));
		$this->urlParameters 	= [];
		$this->url           	= $this->getUrl();
		$requestType 			= $this->getMethod();
		$this->routes        	= $requestType !== 'OPTIONS' ? $routes[$requestType] : [];
		$this->routeError		= $routeError;
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

			$this->callError();
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

	private function callError()
	{
		if ($this->routeError) {
			return $this->callback->handleCallback($this->routeError);
		}

		throw new \Exception("The route could not be found");
	}

	private function handleRoute($route, $urlPath)
	{
		$match 			= PlugHelper::getMatch($route);
		$routeArray     = explode('/', $route);
		$urlArray       = explode('/', $urlPath);
		$sizeUrlArray   = count($routeArray);
		$sizeRouteArray = count($urlArray);
		return $sizeRouteArray === $sizeUrlArray && $match ? $this->mountUrlPath($routeArray, $urlArray, $match) : $route;
	}

	private function mountUrlPath($route, $url, $match)
	{
		foreach ($route as $k => $v) {
			if (in_array($v, $match)) {
				$route[$k]	= $url[$k];
				$key 		= PlugHelper::removeCaracterKey($v);
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