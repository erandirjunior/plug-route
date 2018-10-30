<?php

namespace PlugRoute\Services\Routes;

use PlugRoute\Helpers\PlugHelper;
use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Services\CallbackService;

class DynamicRouteService
{
	private $callback;

	private $data;

	public function __construct()
	{
		$this->callback = new CallbackService();
	}

	public function execute($route, $urlPath)
    {
        $match          = PlugHelper::getMatch($route['route']);
        $route['route'] = str_replace(['{', '}'], '', $route['route']);
        $routeArray     = PlugHelper::toArray($route['route'], '/');
        $urlArray       = PlugHelper::toArray($urlPath, '/');
        $indexes        = PlugHelper::getIndexDynamicOnRoute($routeArray, $match[0]);
        $this->data     = PlugHelper::getValuesDynamics($indexes, $urlArray);
        $route['route'] = $this->mountUrl($routeArray, $urlArray, $indexes);

        if (ValidateHelper::isEqual($route['route'], $urlPath)) {
			return $this->callback->handleCallback($route);
		}
    }

	private function mountUrl($route, $url, $index)
	{
		foreach ($index as $v) {
			if (!empty($url[$v])) {
				$route[$v] = $url[$v];
			}
		}
		$route = implode('/', $route);
		return count($url) > 0 ? '/'.$route : $route;
	}
}