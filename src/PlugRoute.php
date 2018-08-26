<?php

namespace PlugRoute;


use PlugRoute\Helper\RouteHelper;

class PlugRoute
{
    /**
     * Store all routes.
     *
     * @var array
     */
    private $routes;

    /**
     * Receive all routes of type post.
     *
     * @param string $route
     * @param $callback
     */
    public function post(string $route, $callback)
    {
        $this->routes[] = [
            'route' => $route,
            'callback' => $callback,
            'type' => 'POST'
        ];
    }

    /**
     * Receive all routes of type get.
     *
     * @param string $route
     * @param $callback
     */
    public function get(string $route, $callback)
    {
        $this->routes[] = [
            'route' => $route,
            'callback' => $callback,
            'type' => 'GET'
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
        return $this->routes;
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
        return $config->main();
    }
}