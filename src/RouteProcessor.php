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
				if (ValidateHelper::isEqual($this->handleRoute($route['route']), $this->url)) {
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

		Error::showError("The route could not be found");
	}

	private function handleRoute($route)
	{
		$match = PlugHelper::getMatchAll($route, '({.+?(?:\:.*?)?})');

		if (!$match) {
			return $route;
		}

		return $this->manipulateRoute($route, $match);
	}

	public function manipulateRoute($route, $matches)
	{
		$routeReplaced = $route;
		$matchPrepared = [];

		foreach ($matches as $key => $match) {
			$identifiers[] 			= "|##{$key}##|";
			$getMatchRoute 			= PlugHelper::getMatch($match, ':(.*?)}');
			$getMatchRoute          = $getMatchRoute ? $getMatchRoute[1] : null;
			$matchPrepared[$key] 	= $this->getRegex($getMatchRoute, $match, $route);
			$routeReplaced 			= str_replace($match, $identifiers[$key], $routeReplaced);
		}

        $route      = $this->replaces($routeReplaced, $identifiers, $matchPrepared);
		$finalMatch = PlugHelper::getMatch($this->url, "({$route})");
		$this->getDynamicValues($matches, $finalMatch);
		return !empty($finalMatch) ? $finalMatch[1] : null;
	}

	private function getRegex($matchRoute, $index, $route)
	{
		if (is_null($matchRoute)) {
			$lengthHaystack = strstr($route, $index);
			return strlen($lengthHaystack) > strlen($index) ? '(.+?)' : '(.+)';
		}

		return $matchRoute === '?' ? '((?:.+)?)' : "({$matchRoute})";
	}

	private function getDynamicValues($dynamicParameters, $matches)
    {
        $matches = PlugHelper::removeValuesByIndex($matches, [0, 1]);

        foreach ($dynamicParameters as $k => $v) {
            $v          = PlugHelper::replace(['{', '}'], '', $v);
            $strToArray	= PlugHelper::stringToArray($v, ':');
            $value      = $strToArray ? $strToArray[0] : $v;

            if (!empty($matches[$k])) {
				$this->urlParameters[$value] = $matches[$k];
			}
        }
    }

	private function replaces($routePrepared, $identifiers, $matchPrepared)
	{
		$routePrepared 	= PlugHelper::replace('/', '\/', $routePrepared);
		$routePrepared 	= PlugHelper::replace($identifiers, $matchPrepared, $routePrepared);
		$routePrepared	= preg_replace('/(\.\+)(\d|\a|\[)/', '$1?$2', $routePrepared);
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
