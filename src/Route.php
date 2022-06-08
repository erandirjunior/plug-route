<?php

namespace PlugRoute;

use Closure;
use PlugRoute\Action\ClosureAction;
use PlugRoute\Action\ControllerAction;
use PlugRoute\Container\MiddlewareContainer;
use PlugRoute\Container\NamespaceContainer;
use PlugRoute\Container\PrefixContainer;

class Route
{
    private NamespaceContainer $namespaceContainer;

    private string $route;

    private string $name;

    private array $middlewares;

    private object $action;

    private array $rules;

    public function __construct(
        MiddlewareContainer $middlewareContainer,
        NamespaceContainer $namespaceContainer,
        PrefixContainer $prefixContainer,
        string $route
    )
    {
        $this->middlewares = $middlewareContainer->getMiddlewares();
        $this->route = $prefixContainer->getPrefix().$route;
        $this->namespaceContainer = $namespaceContainer;
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
        $namespaces = $this->namespaceContainer->getNamespaces();
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
}