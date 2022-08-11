<?php

namespace PlugRoute\Action;

class ControllerAction
{
    private string $class;

    private string $method;

    public function __construct(string $namespace, string $class, string $method)
    {
        $this->class = $namespace.$class;
        $this->method = $method;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}