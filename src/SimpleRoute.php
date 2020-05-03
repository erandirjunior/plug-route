<?php

namespace PlugRoute;

class SimpleRoute extends RouteAnalyzer
{
    public function __construct()
    {
        $this->parameters = [];
    }

    protected function checkIfCanHandleRoute(string $route, string $url)
    {
        return true;
    }

    protected function handleRoute(string $route, string $url)
    {
        $this->route = $route;
    }
}