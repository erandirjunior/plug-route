<?php

namespace PlugRoute;

use PlugRoute\Helpers\PlugHelper;

class DynamicRoute implements Router
{
	private $route;

	private $urlParameters;

	private $indentifiers;

	private $router;

	public function __construct()
	{
		$this->route			= '';
		$this->urlParameters 	= [];
		$this->indentifiers		= [];
	}

	public function handle(string $route, string $url)
	{
		$matches = PlugHelper::getMatchAll($route, '({.+?(?:\:.*?)?})');

		if ($matches) {
			$this->manipulateRoute($route, $url, $matches);

			return $this;
		}

		return $this->router->handle($route, $url);
	}

	public function getParameters()
	{
		return $this->urlParameters;
	}

	public function next(Router $router)
	{
		$this->router = $router;
	}

	public function route()
	{
		return $this->route;
	}

	public function manipulateRoute($route, $url, $matches)
	{
		$routeReplaced = $route;
		$matchPrepared = [];

		foreach ($matches as $key => $match) {
			$identifiers[] 			= "|##{$key}##|";
			$getMatchRoute 			= PlugHelper::getMatchCase($match, ':(.*?)}');
			$getMatchRoute          = $getMatchRoute ? $getMatchRoute[1] : null;
			$matchPrepared[$key] 	= $this->getRegex($getMatchRoute, $match, $route);
			$routeReplaced 			= str_replace($match, $identifiers[$key], $routeReplaced);
		}

		$route      	= $this->replaces($routeReplaced, $identifiers, $matchPrepared);
		$finalMatch 	= PlugHelper::getMatchCase($url, "({$route})");
		$this->route 	= !empty($finalMatch) ? $finalMatch[1] : null;
		$this->getDynamicValues($matches, $finalMatch);
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
}