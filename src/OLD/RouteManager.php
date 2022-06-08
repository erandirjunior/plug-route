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

	public function __construct(RouteContainer $plugRoute, Request $request)
	{
		$this->request 		= $request;
		$routes 			= $plugRoute->getRoutes();
		$this->callback     = new Callback($this->request);
		$this->routes       = $routes[$this->request->method()];
		$this->errorRoute	= $plugRoute->getErrorRouteNotFound();
	}

	public function run(array $dependencies = [])
	{
		$url = $this->request->getUrl();
        $analyzer = new RouteAnalyzer();

		foreach ($this->routes as $route) {
			$routeHandled = $analyzer->getRoute($route->getRoute(), $url);
			if (ValidateHelper::isEqual($routeHandled, $url)) {
				$this->setParameters($analyzer->getParameters());

				return $this->callback->handlerCallback($route, $dependencies);
			}
		}

		return $this->routeNotFound();
	}

	private function routeNotFound()
	{
		if ($this->errorRoute->getCallback()) {
			return $this->callback->handlerCallback($this->errorRoute);
		}

        $response = new Response();
        $response->setStatusCode(404)->response();
		echo "The route could not be found.";
	}

	private function setParameters($parameters)
	{
		foreach ($parameters as $key => $value) {
			$this->request->setParameter($key, $value);
		}
	}
}