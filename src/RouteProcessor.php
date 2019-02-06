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
		$match = PlugHelper::getMatchAll($route, '({.+?(?:\:.*?)?})');

		if (!$match) {
			return $route;
		}

		return $this->manipulateRoute($route, $this->url, $match);
	}

	public function manipulateRoute($route, $url, $matches)
	{
		$routePrepared = $route;
		$matchPrepared = [];

		foreach ($matches as $key => $match) {
			$identifiers[] 			= "|##{$key}##|";
			$getMatchRoute 			= PlugHelper::getMatch($match, ':(.*?)}');
			$matchPrepared[$key] 	= '.+';

			if (!is_null($getMatchRoute)) {
				$matchPrepared[$key] = $getMatchRoute === '?' ? '(?:.+)?' : $getMatchRoute;
			}

			$routePrepared = str_replace($match, $identifiers[$key], $routePrepared);
		}

		$route = $this->replaces($routePrepared, $identifiers, $matchPrepared);

		return PlugHelper::getMatch($url, "({$route})");
	}

	private function replaces($routePrepared, $identifiers, $matchPrepared)
	{
		$routePrepared 	= str_replace('/', '\/', $routePrepared);
		$routePrepared 	= str_replace($identifiers, $matchPrepared, $routePrepared);
		$routePrepared	= preg_replace('/(\.\+)(\d|\a|\[)/', '$1?$2', $routePrepared);
		$routePrepared	= str_replace('/(\.\+)(\d|\a|\[)/', '$1?$2', $routePrepared);
		return $routePrepared;
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