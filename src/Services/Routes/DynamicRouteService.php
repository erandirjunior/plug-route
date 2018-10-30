<?php

namespace PlugRoute\Services\Routes;

class DynamicRouteService
{
    public function execute()
    {

    }

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
}