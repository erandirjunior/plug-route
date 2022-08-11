<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\Container\MatchTypeRoute;
use PlugRoute\RouteType;

class MatchContainerTest extends TestCase
{
    private MatchTypeRoute $matchContainer;

    protected function setUp(): void
    {
        $this->matchContainer = new MatchTypeRoute();
    }

    public function testAddType()
    {
        $this->matchContainer->addTypes(RouteType::OPTIONS, RouteType::PUT);

        self::assertEquals(['options', 'put'], $this->matchContainer->getTypes());
    }

    public function testAddRoute()
    {
        $this->matchContainer->setRoute('/my-routes');

        self::assertIsString($this->matchContainer->getRoute());
        self::assertEquals('/my-routes', $this->matchContainer->getRoute());
    }

    public function testReset()
    {
        $this->matchContainer->reset();

        self::assertEmpty($this->matchContainer->getRoute());
        self::assertEmpty($this->matchContainer->getTypes());
    }
}