<?php

namespace PlugRoute;

use Closure;
use PlugRoute\Action\ClosureAction;
use PlugRoute\Action\ControllerAction;
use PlugRoute\Container\Setting;

class Route
{
    private string $namespace;

    private string $route;

    private string $name;

    private array $middlewares;

    private object $action;

    private array $rules;

    public function __construct(
        Setting $middlewares,
        Setting $namespaces,
        Setting $prefixes,
        string $route
    )
    {
        $this->middlewares = $middlewares->getData();
        $this->namespace = $this->convertArrayToString($namespaces);
        $this->route = $this->convertArrayToString($prefixes).$route;
        $this->name = '';
        $this->rules = [];
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function name(string $name): Route
    {
        $this->name = $name;
        Cache::set($this);

        return $this;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function callback(Closure $closure): Route
    {
        $this->action = new ClosureAction($closure);

        return $this;
    }

    public function controller(string $controller, string $method): Route
    {
        $namespaces = $this->namespace;
        $this->action = new ControllerAction($namespaces, $controller, $method);

        return $this;
    }

    public function getAction(): object
    {
        return $this->action;
    }

    public function rule(string $parameter, string $rule): Route
    {
        $this->rules[$parameter] = $rule;

        return $this;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    private function convertArrayToString(Setting $namespaces): string
    {
        return implode('', $namespaces->getData());
    }
}