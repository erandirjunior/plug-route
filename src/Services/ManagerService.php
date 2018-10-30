<?php

namespace PlugRoute\Services;

use PlugRoute\Helpers\PlugHelper;
use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Helpers\RouteHelper;
use PlugRoute\Helpers\ValidateHelper;

class ManagerService
{
    private $routes;

    private $countError;

    private $data;

    private $urlPath;

    private $callbackService;

    public function __construct($routes)
    {
        $this->routes           = $routes[RequestHelper::getTypeRequest()];
        $this->callbackService  = new CallbackService();
        $this->urlPath          = RequestHelper::getUrlPath();
    }

    public function manipulateRoutes()
    {
        try {
            array_walk($this->routes, function ($route) {
                $isDynamic = RouteHelper::isDynamic($route['routes']);
                $isDynamic ? $this->handleDynamicRoute($route) : $this->executeCallback($route);
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
        $match          = PlugHelper::getMatch($route['route']);
        $route['route'] = str_replace(['{', '}'], '', $route['route']);
        $routeArray     = PlugHelper::toArray($route['route'], '/');
        $urlArray       = PlugHelper::toArray($this->urlPath, '/');
        $indexes        = PlugHelper::getIndexDynamicOnRoute($routeArray, $match[0]);
        $this->data     = PlugHelper::getValuesDynamics($indexes, $urlArray);
        $route['route'] = $this->mountUrl($routeArray, $urlArray, $indexes);
        $this->executeCallback($route);
    }

    private function executeCallback($route)
    {
        if (!ValidateHelper::isEqual($this->url, $route['route'])) {
            return $this->countError++;
        }
        $callbackService = new CallbackService();
        return $callbackService->handleCallback($this->data);
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
     * Check if number of errors is equal number of routes.
     *
     * @param $value
     *
     * @throws RouteException
     */
    private function countError($value)
    {
        if (ValidateHelper::isEqual(count($value), $this->countError++)) {
            throw new RouteException("Error: route don't exist");
        }
    }
}