<?php

namespace PlugRoute;

class RouteStorage
{
    private $routeType;

    public function __construct()
    {
        $keys = ['GET' , 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'];

        foreach ($keys as $value) {
            $this->routeType[$value] = [];
        }
    }

    public function addRoute(string $routeType, Route $route)
    {
        $this->routeType[$routeType][] = $route;
    }

    /**
     * @return mixed
     */
    public function getRouteTypeByTypeAndIndex(string $type, int $index)
    {
        return $this->routeType[$type][$index];
    }

    /**
     * @return mixed
     */
    public function getAllRoutes()
    {
        return $this->routeType;
    }
}