<?php

namespace PlugRoute\Container;

use PlugRoute\RouteType;

class SettingRouteContainer
{
    private Setting $middleware;

    private Setting $namespace;

    private Setting $prefix;

    private MatchTypeRoute $matchContainer;

    public function __construct()
    {
        $this->middleware = new Setting();
        $this->namespace = new Setting();
        $this->prefix = new Setting();
        $this->matchContainer = new MatchTypeRoute();
    }

    public function getMiddleware(): Setting
    {
        return $this->middleware;
    }

    public function getNamespace(): Setting
    {
        return $this->namespace;
    }

    public function getPrefix(): Setting
    {
        return $this->prefix;
    }

    public function addMiddleware(string ...$middleware)
    {
        $this->middleware->addData(...$middleware);
    }

    public function addNamespace(string ...$namespace)
    {
        $this->namespace->addData(...$namespace);
    }

    public function addPrefix(string ...$prefix)
    {
        $this->prefix->addData(...$prefix);
    }

    public function incrementIndex()
    {
        $this->middleware->incrementIndex();
        $this->namespace->incrementIndex();
        $this->prefix->incrementIndex();
    }

    public function removeLastPosition()
    {
        $this->middleware->removeLastPosition();
        $this->namespace->removeLastPosition();
        $this->prefix->removeLastPosition();
    }

    public function addMatchType(string $route, string ...$types): void
    {
        $this->matchContainer->setRoute($route);
        $this->matchContainer->addTypes(...$types);
    }

    public function getMatchRoute(): string
    {
        return $this->matchContainer->getRoute();
    }

    public function getMatchTypes(): array
    {
        return $this->matchContainer->getTypes();
    }

    public function reset(): void
    {
        $this->matchContainer->reset();
    }
}