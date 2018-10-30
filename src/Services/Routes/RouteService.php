<?php

namespace PlugRoute\Services\Routes;

use PlugRoute\Exceptions\RouteException;
use PlugRoute\Helpers\PlugHelper;
use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Helpers\RouteHelper;
use PlugRoute\Helpers\ValidateHelper;

class RouteService
{
    private $routes;

    private $countError;

    private $data;

    private $urlPath;

    private $callbackService;

    private $simpleRoute;

    private $dynamicRoute;

    public function __construct($routes)
    {
        $this->routes           = $routes[RequestHelper::getTypeRequest()];
        $this->urlPath          = RequestHelper::getUrlPath();
        $this->callbackService  = new CallbackService();
        $this->simpleRoute      = new SimpleRouteService();
        $this->dynamicRoute     = new DynamicRouteService();
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


    private function handleDynamicRoute()
    {
        $this->dynamicRoute->execute();
    }

    private function handleSimpleRoute()
    {
        $this->simpleRoute->execute();
    }

    private function showErrorMessage($message)
    {
        echo $message;
        return $message;
    }

    private function executeCallback($route)
    {
        if (!ValidateHelper::isEqual($this->url, $route['route'])) {
            return $this->countError++;
        }
        $callbackService = new CallbackService();
        return $callbackService->handleCallback($this->data);
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

    private function countError($value)
    {
        if (ValidateHelper::isEqual(count($value), $this->countError++)) {
            throw new RouteException("Error: route don't exist");
        }
    }
}