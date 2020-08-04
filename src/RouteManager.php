<?php

namespace PlugRoute;

use PlugHttp\Response;
use PlugRoute\Callback\Callback;
use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Http\Request;

class RouteManager
{
	private $callback;

	private $routes;

	private $request;

	private $errorRoute;

	private $simpleRoute;

	private $dynamicRoute;

	public function __construct(
		RouteContainer $plugRoute,
		Request $request,
		SimpleRoute $simpleRoute,
		DynamicRoute $dynamicRoute
	)
	{
		$this->request 		= $request;
		$routes 			= $plugRoute->getRoutes();
		$this->callback     = new Callback($this->request);
		$this->routes       = $routes[$this->request->method()];
		$this->simpleRoute	= $simpleRoute;
		$this->dynamicRoute	= $dynamicRoute;
		$this->errorRoute	= $plugRoute->getErrorRouteNotFound();
	}

	public function run(array $dependencies = [])
	{
		$this->dynamicRoute->next($this->simpleRoute);
		$url = $this->request->getUrl();

		foreach ($this->routes as $route) {
			$routerObject = $this->dynamicRoute->handle($route->getRoute(), $url);
			$routeHandled = $routerObject->getRoute();

			if (ValidateHelper::isEqual($routeHandled, $url)) {
				$this->setParameters($routerObject->getParameters());

				return $this->callback->handleCallback($route, $dependencies);
			}
		}

		return $this->routeNotFound();
	}

	private function routeNotFound()
	{
		if ($this->errorRoute->getCallback()) {
			return $this->callback->handleCallback($this->errorRoute);
		}

		$response = new Response();
		$response->setStatusCode(404)->response();
		return Error::throwException("The route could not be found.");
	}

	private function setParameters($parameters)
	{
		foreach ($parameters as $key => $value) {
			$this->request->setParameter($key, $value);
		}
	}
}