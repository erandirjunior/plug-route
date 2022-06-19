<?php

namespace PlugRoute\Container;

class MatchTypeRoute
{
    private string $route;

    private array $types;

    public function __construct()
    {
        $this->route = '';
        $this->types = [];
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    public function addTypes(string ...$types): void
    {
        $this->types = $types;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function reset()
    {
        $this->route = '';
        $this->types = [];
    }
}