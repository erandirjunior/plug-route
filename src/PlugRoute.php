<?php

namespace PlugRoute;


use PlugRoute\Helpers\RouteHelper;
use PlugRoute\Rules\RouteManager;

class PlugRoute
{
    /**
     * Store all routes.
     *
     * @var array
     */
    private $routes;

    private $manager;

    public function __construct()
	{
		$this->manager = new RouteManager();
	}

	/**
     * Receive all routes of type GET.
     *
     * @param string $route
     * @param $callback
     */
    public function get(string $route, $callback)
    {
    	$this->manager->addRoutes($route, $callback, 'GET');
        $this->routes[] = [
            'route' => $route,
            'callback' => $callback,
            'type' => 'GET'
        ];
    }

	/**
	 * Receive all routes of type POST.
	 *
	 * @param string $route
	 * @param $callback
	 */
	public function post(string $route, $callback)
	{
		$this->manager->addRoutes($route, $callback, 'POST');
		$this->routes[] = [
			'route' => $route,
			'callback' => $callback,
			'type' => 'POST'
		];
	}

	/**
	 * Receive all routes of type PUT.
	 *
	 * @param string $route
	 * @param $callback
	 */
	public function put(string $route, $callback)
	{
		$this->manager->addRoutes($route, $callback, 'PUT');
		$this->routes[] = [
			'route' => $route,
			'callback' => $callback,
			'type' => 'POST'
		];
	}

	/**
	 * Receive all routes of type DELETE.
	 *
	 * @param string $route
	 * @param $callback
	 */
	public function delete(string $route, $callback)
	{
		$this->manager->addRoutes($route, $callback, 'DELETE');
		$this->routes[] = [
			'route' => $route,
			'callback' => $callback,
			'type' => 'POST'
		];
	}

	/**
	 * Receive all routes of type DELETE.
	 *
	 * @param string $route
	 * @param $callback
	 */
	public function patch(string $route, $callback)
	{
		$this->manager->addRoutes($route, $callback, 'PATCH');
		$this->routes[] = [
			'route' => $route,
			'callback' => $callback,
			'type' => 'POST'
		];
	}

    /**
     * Receives grouping of routes.
     *
     * @param string $route
     * @param callable $callback
     */
    public function group(string $route, callable $callback)
    {
		$this->manager->manipulateRouteGroup($route, $callback);
        $this->routes = $this->mountRouteGroup($route, $callback);
    }

    /**
     * Receive routes of all types.
     *
     * @param string $route
     * @param $callback
     */
    public function any(string $route, $callback)
    {
		$this->manager->manipulateRouteTypeAny($route, $callback);
        $this->routes[] = [
            'route' => $route,
            'callback' => $callback,
            'type' => 'ANY'
        ];
    }

    /**
     * Return routes.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->manager->getRoutes();
    }

    /**
     * Mount routes that are grouped.
     *
     * @param $route
     * @param callable $callback
     * @return array
     */
    private function mountRouteGroup($route, $callback)
    {
    	$this->manager->addRouteGroup($route, $callback);
        $routesBeforeGroup = $this->routes;
        $callback($this);

        foreach ( $this->routes as $key => $value) {
            if (empty($routesBeforeGroup[$key])) {
                $this->routes[$key]['route'] = RouteHelper::pathRoute($route, $value['route']);
            }
        }

        return $this->routes;
    }

    /**
     * Execute routes.
     *
     * @return string
     */
    public function on()
    {
        $config = new PlugConfig($this->routes);
        //$config = new PlugConfig($this->getRoutes());
        return $config->main();
    }
}