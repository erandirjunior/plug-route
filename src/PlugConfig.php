<?php

namespace PlugRoute;

use PlugRoute\Exceptions\ClassException;
use PlugRoute\Exceptions\MethodException;
use PlugRoute\Exceptions\RouteException;
use PlugRoute\Helpers\PlugHelper;
use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Helpers\RouteHelper;
use PlugRoute\Helpers\ValidateHelper;

class PlugConfig
{
    /**
     * Receive all routes.
     *
     * @var array
     */
    private $routes;

    /**
     * Receive number of errors.
     *
     * @var int
     */
    private $countError;

    /**
     * Receive array of dynamic values.
     *
     * @var array
     */
    private $data;

    /**
     * Receive url path.
     *
     * @var string
     */
    private $url;

    /**
     * PlugConfig constructor.
     *
     * @param array $routes
     */
    public function __construct($routes)
    {
        $this->routes = $routes;
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
     * Process all routes.
     *
     * @return string
     */
    public function main()
    {
        try {
            $this->url = RequestHelper::getUrlPath();
            $this->routes = RouteHelper::filterRoute($this->routes);
            array_walk($this->routes, function ($route) {
                $this->handleRoute($route);
            });
        } catch (\Exception $e) {
            return $this->showErrorMessage($e->getMessage());
        }
    }

    /**
     * Show and return error message.
     *
     * @param string $message
     *
     * @return mixed
     */
    private function showErrorMessage($message)
    {
        echo $message;
        return $message;
    }

    /**
     * Handle routes, verifying if route is dynamic or not.
     *
     * @param array $route
     * @param string $url
     *
     * @throws \Exception
     */
    private function handleRoute($route)
    {
        if (RouteHelper::isDynamic($route['route'])) {
            $this->handleDynamicRoute($route);
        } else {
            $this->handleCallback($route);
        }
        $this->countError($this->routes);
    }

    /**
     * Handle dynamic route.
     *
     * @param array $route
     * @param string $url
     *
     * @return int|mixed
     *
     * @throws \Exception
     */
    private function handleDynamicRoute($route)
    {
        $match = PlugHelper::getMatch($route['route']);
        $route['route'] = str_replace(['{', '}'], '', $route['route']);
        $routeArray = PlugHelper::toArray($route['route'], '/');
        $urlArray = PlugHelper::toArray($this->url, '/');
        $indexes = PlugHelper::getIndexDynamicOnRoute($routeArray, $match[0]);
        $this->data = PlugHelper::getValuesDynamics($indexes, $urlArray);
        $route['route'] = $this->mountUrl($routeArray, $urlArray, $indexes);
        return $this->handleCallback($route);
    }

    /**
     * Return path dynamic route.
     *
     * @param $route
     * @param $url
     * @param $index
     *
     * @return string
     */
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

    /**
     * Handle callback.
     * Can call manipulator object.
     *
     * @param string $url
     * @param array $route
     *
     * @return int|mixed
     * @throws \Exception
     */
    private function handleCallback(array $route)
    {
        if (!ValidateHelper::isEqual($this->url, $route['route'])) {
            return $this->countError++;
        }
        if (is_callable($route['callback'])) {
            return $this->callFunction($route['callback']);
        }
        return $this->handleObject($route);
    }

    /**
     * Treat route that handle object.
     *
     * @param array $route
     *
     * @return mixed
     * @throws \Exception
     */
    private function handleObject($route)
    {
        $callback = explode("@", $route['callback']);
        $instance = $this->createInstance($callback[0]);
        return $this->callMethod($instance, $callback[1]);
    }

    /**
     * Create a instance of class.
     * Return instance.
     *
     * @param string $class
     *
     * @return mixed
     * @throws ClassException
     */
    private function createInstance($class)
    {
        if (!ValidateHelper::classExist($class)) {
            throw new ClassException("Error: class don't exist.");
        }
        return new $class;
    }

    /**
     * @param object $instance
     * @param string $method
     *
     * @return mixed
     * @throws MethodException
     */
    private function callMethod($instance, $method)
    {
        if (ValidateHelper::methodExist($instance, $method)) {
            return $instance->$method();
        }
        throw new MethodException("Error: method don't exist.");
    }

    /**
     * Invoke function.
     *
     * @param callable $function
     *
     * @return mixed
     */
    private function callFunction($function)
    {
        return $function($this->data);
    }

    /**
     * Check if number of errors is equal number of routes.
     *
     * @param $value
     *
     * @throws RouteException
     */
    private function countError($value)
    {
        if (ValidateHelper::isEqual(count($value), $this->countError)) {
            throw new RouteException("Error: route don't exist");
        }
    }
}