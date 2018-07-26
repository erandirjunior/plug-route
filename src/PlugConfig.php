<?php
/**
 * Created by PhpStorm.
 * User: erandir
 * Date: 25/07/18
 * Time: 19:12
 */

namespace PlugRoute;


use PlugRoute\Helper\PlugHelper;

// TODO: check class
class PlugConfig
{
    /**
     * Receive routes.
     *
     * @var array
     */
    private $routes;
    private $countError;

    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param string $url
     * @see isDynamicRoute
     * @see handleDynamicRoute
     * @see execute
     * @see countError
     */
    public function main()
    {
        try {
            $url = PlugHelper::getUrlPath();
            $this->routes = PlugHelper::filter($this->routes);
            array_walk($this->routes, function($route) use ($url) {
                if (PlugHelper::isDynamic($route['route'])) {
                    $this->handleDynamicRoute($route, $url);
                } else {
                    $this->execute($url, $route);
                }
                $this->countError($this->routes);
            });
        } catch (\Exception $e) {
            $message = $e->getMessage();
            echo $message;
            return $message;
        }
    }

    private function handleDynamicRoute($route, $url)
    {
        $match          = PlugHelper::getMatch($route['route']);
        $route['route'] = str_replace(['{', '}'], '', $route['route']);
        $routeArray     = explode('/', $route['route']);
        $urlArray       = explode('/', $url);

        if (count($urlArray) > count($routeArray)) {
            array_unshift($routeArray, "");
        }
        $index = PlugHelper::getIndex($routeArray, $match[0]);

        foreach ($routeArray as $k => $v) {
            $routeArray[$k] = ($k === $index[$k]) ? $urlArray[$k] : $routeArray[$k];
        }

        $route['route'] = implode('/', $routeArray);
        return $this->execute($url, $route);
    }

    private function execute(string $url, array $route)
    {
        if (PlugHelper:: $url === $route['route']) {
            if (is_callable($route['callback'])) {
                return $route['callback']();
            }
            $callback   = explode("@", $route['callback']);
            $class      = $callback[0];
            $method     = $callback[1];
            $instance   = $this->createInstance($class);
            return $this->action($instance, $method);
        }
        $this->countError ++;
    }

    private function createInstance($class)
    {
        if (!class_exists($class)) {
            throw new \Exception("Error: class don't exist.");
        }
        $instance = new $class;
        return $instance;
    }

    private function action($instance, $method)
    {
        if (method_exists($instance, $method)) {
            return $instance->$method();
        }
        throw new \Exception("Error: method don't exist.");
    }

    private function countError($value)
    {
        if (count($value) === $this->countError) {
            throw new \Exception("Error: route don't exist");
        }
    }
}