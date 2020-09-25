<?php

namespace PlugRoute;

abstract class RouteAnalyzer
{
    private $routerAnalyzer;

    protected $route;

    protected $parameters;

    public function next(RouteAnalyzer $routeAnalyzer)
    {
        $this->routerAnalyzer = $routeAnalyzer;
    }

    public function handle(string $route, string $url)
    {
        if ($this->checkIfCanHandlerRoute($route, $url)) {
            $this->routeHandler($route, $url);

            return $this;
        }

        return $this->routerAnalyzer->handle($route, $url);
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    abstract protected function checkIfCanHandlerRoute(string $route, string $url);

    abstract protected function routeHandler(string $route, string $url);
}