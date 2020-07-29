<?php

namespace PlugRoute;

class Route
{
    private $route;

    private $callback;

    private $name;

    private $middlewares;

    /**
     * Route constructor.
     * @param string $route
     * @param string $callback
     * @param string $name
     * @param array $middlewares
     */
    public function __construct($route = '', $callback = '', $name = '', $middlewares = [])
    {
        $this->route = $route;
        $this->callback = $callback;
        $this->name = $name;
        $this->middlewares = $middlewares;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param mixed $callback
     */
    public function setCallback($callback): void
    {
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @param array $middlewares
     */
    public function setMiddlewares($middlewares): void
    {
        $this->middlewares = $middlewares;
    }
}