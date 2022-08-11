<?php

namespace PlugRoute;

use PlugRoute\Callback\Callback;
use PlugRoute\Container\RouteContainer;
use PlugRoute\Http\Request;

class RouteHandle
{
	private Callback $callback;

    private Request $request;

    private array $routes;

	private array $fallback;

	public function __construct(RouteContainer $routeContainer, Request $request)
	{
		$this->request = $request;
        $this->routes = $routeContainer->getRoutesByType($this->request->method());
        $this->fallback = $routeContainer->getFallbackRoute();
		$this->callback = new Callback($this->request);
	}

	public function run()
	{
        $matchRoute = new MatchRoute($this->request->getUrl());

		foreach ($this->routes as $route) {
            if ($matchRoute->urlAndRouteAreEqual($route)) {
                $this->request->addParameters($matchRoute->getParameters());

                return $this->callback->handlerCallback($route);
            }
		}

        return $this->throwFallback();
	}

	private function throwFallback()
	{
        foreach ($this->fallback as $error) {
            return $this->callback->handlerCallback($error);
        }

        Error::throwException("The route could not be found.");
	}
}