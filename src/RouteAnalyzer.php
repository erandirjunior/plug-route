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
        if ($this->checkIfCanHandleRoute($route, $url)) {
            $this->handleRoute($route, $url);

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

    abstract protected function checkIfCanHandleRoute(string $route, string $url);

    abstract protected function handleRoute(string $route, string $url);
}