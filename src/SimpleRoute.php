<?php

namespace PlugRoute;

class SimpleRoute extends RouteAnalyzer
{
    public function __construct()
    {
        $this->parameters = [];
    }

    protected function checkIfCanHandlerRoute(string $route, string $url)
    {
        return true;
    }

    protected function routeHandler(string $route, string $url)
    {
        $this->route = $route;
    }
}