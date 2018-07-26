<?php

namespace PlugRoute;


use PlugRoute\Helper\PlugHelper;

class PlugRoute
{
    private $config;

    private $routes;

    public function __construct()
    {}

    /**
     * Receive requests type post.
     *
     * @param array $post
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
     * Receive requests type get.
     *
     * @param array $get
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
     * Receive a group of requests
     *
     * @param $route string
     * @param $callback callable
     */
    public function group(string $route, callable $callback)
    {
        $routesBeforeGroup = $this->routes;
        $callback($this);

        foreach ($this->routes as $key => $value) {
            if (empty($routesBeforeGroup[$key])) {
                $this->routes[$key]['route'] = PlugHelper::pathRoute($route, $value['route']);
            }
        }
    }

    public function any(string $route, $callback)
    {
        $this->routes[] = [
            'route' => $route,
            'callback' => $callback,
            'type' => 'ANY'
        ];
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function on()
    {
        $config = new PlugConfig($this->routes);
        return $config->main();
//        return $config->run();
    }
}