<?php

namespace PlugRoute;

use PlugRoute\Container\SettingRouteContainer;
use PlugRoute\Container\RouteContainer;
use PlugRoute\Http\Request;

class PlugRoute
{
    private RouteContainer $routeContainer;

    private SettingRouteContainer $settingRouteContainer;

    private Request $request;

    public function __construct()
    {
        $this->routeContainer = new RouteContainer();
        $this->settingRouteContainer = new SettingRouteContainer();
        $this->request = new Request();
    }

    public function middleware(string ...$middleware): PlugRoute
    {
        $this->settingRouteContainer->addMiddleware(...$middleware);
        return $this;
    }

    public function namespace(string ...$namespace): PlugRoute
    {
        $this->settingRouteContainer->addNamespace(...$namespace);
        return $this;
    }

    public function prefix(string ...$prefix): PlugRoute
    {
        $this->settingRouteContainer->addPrefix(...$prefix);
        return $this;
    }

    public function group(callable $callback)
    {
        $this->settingRouteContainer->incrementIndex();
        $callback($this);
        $this->settingRouteContainer->removeLastPosition();
    }

    public function get(string $route): Route
    {
        return $this->addRouteInContainer($route, RouteType::GET);
    }

    public function post(string $route): Route
    {
        return $this->addRouteInContainer($route, RouteType::POST);
    }

    public function delete(string $route): Route
    {
        return $this->addRouteInContainer($route, RouteType::DELETE);
    }

    public function put(string $route): Route
    {
        return $this->addRouteInContainer($route, RouteType::PUT);
    }

    public function options(string $route): Route
    {
        return $this->addRouteInContainer($route, RouteType::OPTIONS);
    }

    public function patch(string $route): Route
    {
        return $this->addRouteInContainer($route, RouteType::PATCH);
    }

    public function fallback(): Route
    {
        return $this->addRouteInContainer('', RouteType::FALLBACK);
    }

    public function redirect(string $from, string $to, int $code = 301): void
    {
        $this->addRouteInContainer($from, RouteType::GET)
            ->callback(fn() => $this->request->redirect($to, $code));
    }

    private function addRouteInContainer(string $path, string $type): Route
    {
        $route = $this->createRouteInstance($path);
        $this->routeContainer->addRoute($route, $type);
        return $route;
    }

    private function createRouteInstance(string $path): Route
    {
        return new Route(
            $this->settingRouteContainer->getMiddleware(),
            $this->settingRouteContainer->getNamespace(),
            $this->settingRouteContainer->getPrefix(),
            $path
        );
    }

    public function match(string $route, string ...$types): PlugRoute
    {
        $this->settingRouteContainer->addMatchType($route, ...$types);
        return $this;
    }

    public function any(string $route): PlugRoute
    {
        $this->settingRouteContainer->addMatchType($route, ...RouteType::getTypes());
        return $this;
    }

    public function controller(string $controller, $method)
    {
        $route = $this->settingRouteContainer->getMatchRoute();

        foreach ($this->settingRouteContainer->getMatchTypes() as $type) {
            $this->$type($route)->controller($controller, $method);
        }

        $this->settingRouteContainer->reset();
    }

    public function callback(callable $callback)
    {
        $route = $this->settingRouteContainer->getMatchRoute();

        foreach ($this->settingRouteContainer->getMatchTypes() as $type) {
            $this->$type($route)->callback($callback);
        }

        $this->settingRouteContainer->reset();
    }

    public function run()
    {
        $manager = new RouteHandle($this->routeContainer, $this->request);

        echo $manager->run();
    }
}