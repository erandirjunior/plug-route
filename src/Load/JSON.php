<?php

namespace PlugRoute\Load;

use Closure;
use PlugRoute\PlugRoute;
use PlugRoute\Route;

class JSON
{
    private PlugRoute $plugRoute;

    public function __construct(PlugRoute $plugRoute)
    {
        $this->plugRoute = $plugRoute;
    }

    public function loadRoutesFromFile(string $file)
    {
        $data = $this->getDataFromFile($file);

        $this->runRoutes($data);
    }

    private function getDataFromFile(string $file): array
    {
        $content = file_get_contents($file);
        $content = json_decode($content);

        return is_array($content) ? $content : [$content];
    }

    private function runRoutes(array $data): void
    {
        foreach ($data as $route) {
            $this->beforeRoute($route);
            $this->plugRoute->group($this->getGroupClosure($route));
        }
    }

    private function beforeRoute($route): void
    {
        $this->addMiddlewareIfExists($route);
        $this->addPrefixIfExists($route);
        $this->addNamespaceIfExists($route);
    }

    private function addMiddlewareIfExists($route)
    {
        if (isset($route->middlewares)) {
            $this->plugRoute->middleware(...$route->middlewares);
        }
    }

    private function addPrefixIfExists($route)
    {
        if (isset($route->prefix)) {
            $this->plugRoute->prefix(...$route->prefix);
        }
    }

    private function addNamespaceIfExists($route)
    {
        if (isset($route->namespace)) {
            $this->plugRoute->namespace(...$route->namespace);
        }
    }

    private function getGroupClosure($route): Closure
    {
        return fn() => $route->group ? $this->runRoutes($route->group) : $this->addRoute($route);
    }

    private function addRoute(object $route)
    {
        $type = strtolower($route->type);

        $routeObject = $this->plugRoute->$type($route->path)
            ->name($route->name ?? '')
            ->controller($route->class, $route->method);

        $this->setParameterRulesIfSent($route, $routeObject);
    }

    private function setParameterRulesIfSent(object $route, Route $routeObject): void
    {
        if (!empty($route->rules)) {
            $routeObject->rule(...$route->rules);
        }
    }
}