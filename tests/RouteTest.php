<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\Action\ControllerAction;
use PlugRoute\Container\MiddlewareContainer;
use PlugRoute\Container\NamespaceContainer;
use PlugRoute\Container\PrefixContainer;
use PlugRoute\Container\Setting;
use PlugRoute\Route;

class RouteTest extends TestCase
{
    private Setting $middlewareContainer;

    private Setting $namespaceContainer;

    private Setting $prefixContainer;

    protected function setUp(): void
    {
        $this->middlewareContainer = new Setting();
        $this->namespaceContainer = new Setting();
        $this->prefixContainer = new Setting();
    }

    public function testSimpleRoute()
    {
        $route = new Route(
            $this->middlewareContainer,
            $this->namespaceContainer,
            $this->prefixContainer,
            '/home'
        );

        self::assertEquals('/home', $route->getRoute());
    }

    public function testMiddleware()
    {
        $this->middlewareContainer->addData(\stdClass::class);
        $route = new Route(
            $this->middlewareContainer,
            $this->namespaceContainer,
            $this->prefixContainer,
            '/test'
        );

        $route->callback(function () {});

        self::assertEquals([\stdClass::class], $route->getMiddlewares());
    }

    public function testNamespace()
    {
        $this->namespaceContainer->addData('\App');
        $route = new Route(
            $this->middlewareContainer,
            $this->namespaceContainer,
            $this->prefixContainer,
            '/test'
        );

        $route->controller('\Controller', 'method');
        $action = $route->getAction();

        self::assertInstanceOf(ControllerAction::class, $action);
        self::assertEquals('\App\Controller', $action->getClass());
        self::assertEquals('method', $action->getMethod());
    }

    public function testPrefix()
    {
        $this->prefixContainer->addData('/news');
        $route = new Route(
            $this->middlewareContainer,
            $this->namespaceContainer,
            $this->prefixContainer,
            '/sports'
        );

        self::assertEquals('/news/sports', $route->getRoute());
    }

    public function testName()
    {
        $route = new Route(
            $this->middlewareContainer,
            $this->namespaceContainer,
            $this->prefixContainer,
            '/sports'
        );

        $route->name('sports.page');

        self::assertEquals('sports.page', $route->getName());
    }

    public function testCompleteControllerRoute()
    {
        $this->middlewareContainer->addData(\PDO::class);
        $this->middlewareContainer->addData(\ArrayObject::class);
        $this->namespaceContainer->addData('\App');
        $this->namespaceContainer->addData('\Controller');
        $this->prefixContainer->addData('/news');
        $this->prefixContainer->addData('/sports');

        $route = new Route(
            $this->middlewareContainer,
            $this->namespaceContainer,
            $this->prefixContainer,
            '/f1'
        );

        $route->controller('\MyController', 'run');
        $route->name('news.sports.f1', 'run');
        $action = $route->getAction();

        self::assertEquals('/news/sports/f1', $route->getRoute());
        self::assertEquals([\PDO::class, \ArrayObject::class], $route->getMiddlewares());
        self::assertEquals('news.sports.f1', $route->getName());
        self::assertEquals('\App\Controller\MyController', $action->getClass());
        self::assertEquals('run', $action->getMethod());
    }

    public function testCompleteCallbackRoute()
    {
        $this->middlewareContainer->addData(\PDO::class);
        $this->middlewareContainer->addData(\ArrayObject::class);
        $this->prefixContainer->addData('/news');
        $this->prefixContainer->addData('/sports');

        $route = new Route(
            $this->middlewareContainer,
            $this->namespaceContainer,
            $this->prefixContainer,
            '/f1'
        );

        $route->callback(function () {});
        $route->name('news.sports.f1', 'run');

        self::assertEquals('/news/sports/f1', $route->getRoute());
        self::assertEquals([\PDO::class, \ArrayObject::class], $route->getMiddlewares());
        self::assertEquals('news.sports.f1', $route->getName());
        self::assertIsObject($route->getAction());
    }
}