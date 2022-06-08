<?php

namespace PlugRoute;

use PlugRoute\Container\Container;
use PlugRoute\Container\RouteContainer;
use PlugRoute\Http\Request;
use PlugRoute\Load\JSON;

class PlugRoute
{
    private RouteContainer $routeContainer;

    private Container $container;

    private JSON $json;

    private Request $request;

    public function __construct()
    {
        $this->routeContainer = new RouteContainer();
        $this->container = new Container();
        $this->json = new JSON($this);
        $this->request = new Request();
    }

    public function middleware(string ...$middleware): PlugRoute
    {
        $this->container->addMiddleware(...$middleware);
        return $this;
    }

    public function namespace(string $namespace): PlugRoute
    {
        $this->container->addNamespace($namespace);
        return $this;
    }

    public function prefix(string $prefix): PlugRoute
    {
        $this->container->addPrefix($prefix);
        return $this;
    }

    public function group(callable $callback)
    {
        $this->container->incrementIndex();
        $callback($this);
        $this->container->removeLastPosition();
    }

    public function getRouteContainer(): RouteContainer
    {
        return $this->routeContainer;
    }

    public function get(string $route): Route
    {
        return $this->createAndAddNewRouteInContainer($route, RouteType::GET);
    }

    public function post(string $route): Route
    {
        return $this->createAndAddNewRouteInContainer($route, RouteType::POST);
    }

    public function delete(string $route): Route
    {
        return $this->createAndAddNewRouteInContainer($route, RouteType::DELETE);
    }

    public function put(string $route): Route
    {
        return $this->createAndAddNewRouteInContainer($route, RouteType::PUT);
    }

    public function options(string $route): Route
    {
        return $this->createAndAddNewRouteInContainer($route, RouteType::OPTIONS);
    }

    public function patch(string $route): Route
    {
        return $this->createAndAddNewRouteInContainer($route, RouteType::PATCH);
    }

    public function fallback(): Route
    {
        return $this->createAndAddNewRouteInContainer('fallback', RouteType::FALLBACK);
    }

    public function redirect(string $from, string $to, int $code = 301): void
    {
        $this->createAndAddNewRouteInContainer($from, RouteType::GET)
            ->callback(fn() => $this->request->redirect($to, $code));
    }

    private function createAndAddNewRouteInContainer(string $path, string $type): Route
    {
        $route = new Route(
            $this->container->getMiddleware(),
            $this->container->getNamespace(),
            $this->container->getPrefix(),
            $path
        );
        $this->routeContainer->addRoute($route, $type);
        return $route;
    }

    public function match(string $route, string ...$types): PlugRoute
    {
        $this->container->addMatchType($route, ...$types);
        return $this;
    }

    public function any(string $route): PlugRoute
    {
        $this->container->addMatchType($route, ...RouteType::getTypes());
        return $this;
    }

    public function controller(string $controller, $method)
    {
        $route = $this->container->getMatchRoute();

        foreach ($this->container->getMatchTypes() as $type) {
            $this->$type($route)->controller($controller, $method);
        }

        $this->container->reset();
    }

    public function callback(callable $callback)
    {
        $route = $this->container->getMatchRoute();

        foreach ($this->container->getMatchTypes() as $type) {
            $this->$type($route)->callback($callback);
        }

        $this->container->reset();
    }

    public function fromJsonFile(string $file)
    {
        $this->json->loadRoutesFromFile($file);
    }

    public function run()
    {
        $manager = new RouteHandle($this->routeContainer, $this->request);

        echo $manager->run([]);
    }
}