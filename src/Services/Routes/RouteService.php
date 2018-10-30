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

    private $urlPath;

    private $simpleRoute;

    private $dynamicRoute;

    public function __construct($routes)
    {
        $this->routes           = $routes[RequestHelper::getTypeRequest()];
        $this->urlPath          = RequestHelper::getUrlPath();
        $this->simpleRoute      = new SimpleRouteService();
        $this->dynamicRoute     = new DynamicRouteService();
    }

    public function manipulateRoutes()
    {
        try {
            array_walk($this->routes, function ($route) {
                $isDynamic = RouteHelper::isDynamic($route['route']);
                $isDynamic ? $this->handleDynamicRoute($route) : $this->handleSimpleRoute($route);
            });
        } catch (\Exception $e) {
            return $this->showErrorMessage($e->getMessage());
        }
    }


    private function handleDynamicRoute($route)
    {
        $this->dynamicRoute->execute($route, $this->urlPath);
    }

    private function handleSimpleRoute($route)
    {
        return $this->simpleRoute->execute($route, $this->urlPath);
    }

    private function showErrorMessage($message)
    {
        echo $message;
        return $message;
    }

    private function countError($value)
    {
        if (ValidateHelper::isEqual(count($value), $this->countError++)) {
            throw new RouteException("Error: route don't exist");
        }
    }
}