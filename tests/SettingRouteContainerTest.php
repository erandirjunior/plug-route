<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\Container\SettingRouteContainer;

class SettingRouteContainerTest extends TestCase
{
    private SettingRouteContainer $settingRouteContainer;

    protected function setUp(): void
    {
        $this->settingRouteContainer = new SettingRouteContainer();
    }

    public function testMiddlewareSimpleValue()
    {
        $this->settingRouteContainer->addMiddleware(\PDO::class);
        self::assertEquals([\PDO::class], $this->settingRouteContainer->getMiddleware()->getData());
    }

    public function testMiddlewareMultipleValues()
    {
        $this->settingRouteContainer->addMiddleware(\PDO::class);
        $this->settingRouteContainer->incrementIndex();
        $this->settingRouteContainer->addMiddleware(\Reflection::class);
        $middlewares = $this->settingRouteContainer->getMiddleware()->getData();
        self::assertEquals([\PDO::class, \Reflection::class], $middlewares);
    }

    public function testRemoveLastMiddlewarePosition()
    {
        $this->settingRouteContainer->addMiddleware(\PDO::class);
        $this->settingRouteContainer->incrementIndex();
        $this->settingRouteContainer->addMiddleware(\Reflection::class);
        $this->settingRouteContainer->removeLastPosition();
        self::assertEquals([\PDO::class], $this->settingRouteContainer->getMiddleware()->getData());
    }

    public function testPrefixSimpleValue()
    {
        $this->settingRouteContainer->addPrefix('/sports');
        self::assertEquals(['/sports'], $this->settingRouteContainer->getPrefix()->getData());
    }

    public function testPrefixMultipleValues()
    {
        $this->settingRouteContainer->addPrefix('/sports');
        $this->settingRouteContainer->incrementIndex();
        $this->settingRouteContainer->addPrefix('/soccer', '/teams');
        $prefixes = $this->settingRouteContainer->getPrefix()->getData();
        self::assertEquals(['/sports', '/soccer', '/teams'], $prefixes);
    }

    public function testRemoveLastPrefixPosition()
    {
        $this->settingRouteContainer->addPrefix('/soccer');
        $this->settingRouteContainer->incrementIndex();
        $this->settingRouteContainer->addPrefix('/teams');
        $this->settingRouteContainer->removeLastPosition();

        self::assertEquals(['/soccer'], $this->settingRouteContainer->getPrefix()->getData());
    }

    public function testNamespaceSimpleValue()
    {
        $this->settingRouteContainer->addNamespace('\\Controllers');

        self::assertEquals(['\\Controllers'], $this->settingRouteContainer->getNamespace()->getData());
    }

    public function testNamespaceMultipleValues()
    {
        $this->settingRouteContainer->addNamespace('\\Controllers');
        $this->settingRouteContainer->incrementIndex();
        $this->settingRouteContainer->addNamespace('\\User', '\\UserController');
        $namespaces = $this->settingRouteContainer->getNamespace()->getData();
        self::assertEquals(['\\Controllers', '\\User', '\\UserController'], $namespaces);
    }

    public function testRemoveLastNamespacePosition()
    {
        $this->settingRouteContainer->addNamespace('\\Controllers');
        $this->settingRouteContainer->incrementIndex();
        $this->settingRouteContainer->addNamespace('\\UserController');
        $this->settingRouteContainer->removeLastPosition();
        self::assertEquals(['\\Controllers'], $this->settingRouteContainer->getNamespace()->getData());
    }
}