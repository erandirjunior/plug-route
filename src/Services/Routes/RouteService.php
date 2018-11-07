<?php

namespace PlugRoute\Services\Routes;

use PlugRoute\Exceptions\RouteException;
use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Helpers\RouteHelper;
use PlugRoute\Helpers\ValidateHelper;

class RouteService
{
    private $routes;

    public static $accountUrlNotFound;

    private $urlPath;

    private $simpleRoute;

    private $dynamicRoute;

    public function __construct($routes)
    {
        $typeRequest            = RequestHelper::getTypeRequest();
        $this->routes           = $typeRequest !== 'OPTIONS' ? $routes[$typeRequest] : [];
        $this->urlPath          = RequestHelper::getUrlPath();
        $this->simpleRoute      = new SimpleRouteService($routes);
        $this->dynamicRoute     = new DynamicRouteService($routes);
    }

    public function manipulateRoutes()
    {
        try {
            array_walk($this->routes, function ($route) {
                $isDynamic = RouteHelper::isDynamic($route['route']);
                $isDynamic ? $this->handleDynamicRoute($route) : $this->handleSimpleRoute($route);
                $this->countError($route);
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
    }

    private function countError()
    {
        if (ValidateHelper::isEqual(count($this->routes), self::$accountUrlNotFound)) {
            throw new RouteException("Error: route don't exist");
        }
    }
}