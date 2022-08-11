<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\Container\MiddlewareContainer;
use PlugRoute\Container\NamespaceContainer;
use PlugRoute\Container\PrefixContainer;
use PlugRoute\Container\RouteContainer;
use PlugRoute\Container\Setting;
use PlugRoute\Route;
use PlugRoute\RouteType;

class RouteContainerTest extends TestCase
{
    private Setting $middlewareContainer;

    private Setting $namespaceContainer;

    private Setting $prefixContainer;

    private RouteContainer $routeC;

    protected function setUp(): void
    {
        $this->middlewareContainer = new Setting();
        $this->namespaceContainer = new Setting();
        $this->prefixContainer = new Setting();
        $this->routeC = new RouteContainer();
    }

    public function testContainer()
    {
        $route = new Route(
            $this->middlewareContainer,
            $this->namespaceContainer,
            $this->prefixContainer,
            '/home'
        );

        $this->routeC->addRoute($route, RouteType::GET);
        $this->routeC->addRoute($route, RouteType::POST);
        $this->routeC->addRoute($route, RouteType::POST);

        $total = count($this->routeC->getRoutes());
        $totalGet = count($this->routeC->getRoutesByType(RouteType::GET));
        $totalPost = count($this->routeC->getRoutesByType(RouteType::POST));

        self::assertEquals(2, $total);
        self::assertEquals(1, $totalGet);
        self::assertEquals(2, $totalPost);
    }
}