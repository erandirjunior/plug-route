<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\RouteContainer;
use PlugRoute\RouteManager;
use PlugRoute\Test\Classes\MiddlewareExample;

final class RouteManagerTest extends TestCase
{
    private $instance;

    public function setUp()
    {
    	$container = new RouteContainer();

    	$container->addRoute('GET', '/', function() {
    		return 10;
		});

    	$request = \PlugRoute\Test\Classes\RequestCreator::create();
        $this->instance = new RouteManager($container, $request);
    }

    public function testRouteReturn()
    {
		$expected = $this->instance->run();

		$this->assertEquals($expected, 10);
    }

    public function testDynamicRouteReturn()
    {
		$container = new RouteContainer();

		$container->addRoute('GET', '/{test}', function() {
			return 10;
		});

		$request = \PlugRoute\Test\Classes\RequestCreator::createDynamic();
		$this->instance = new RouteManager($container, $request);
		$actual = $this->instance->run();

		$this->assertEquals(10, $actual);
    }

	/**
	 * @testQueryExcept
	 * @runInSeparateProcess
	 **/
    public function testErrorRoute()
    {
		$this->expectException(\Exception::class);

		$container 		= new RouteContainer();
		$request 		= \PlugRoute\Test\Classes\RequestCreator::createDynamic();
		$this->instance = new RouteManager($container, $request);

		$this->instance->run();
    }

	/**
	 * @testQueryExcept
	 * @runInSeparateProcess
	 **/
    public function testErrorRouteDefined()
    {
		$container 		= new RouteContainer();
		$request 		= \PlugRoute\Test\Classes\RequestCreator::createDynamic();
		$container->setErrorRouteNotFound(function() {
			return 'There was error';
		});

		$this->instance = new RouteManager($container, $request);
		$actual = $this->instance->run();

		self::assertEquals('There was error', $actual);
    }

    public function testOptionalDynamicRoute()
    {
		$container = new RouteContainer();

		$container->addRoute('GET', '/{test:?}', function() {
			return 10;
		});

		$request = \PlugRoute\Test\Classes\RequestCreator::createDynamic();
		$this->instance = new RouteManager($container, $request);
		$expected = $this->instance->run();

		$this->assertEquals($expected, 10);
    }

	public function testMiddlewareFLow()
	{
		$container = new RouteContainer();

		$container->addRoute('GET', '/{test}', function(\PlugRoute\Http\Request $request) {
			return $request->input('test');
		})->setMiddleware([MiddlewareExample::class]);

		$request = \PlugRoute\Test\Classes\RequestCreator::createDynamic();
		$this->instance = new RouteManager($container, $request);
		$actual = $this->instance->run();

		$this->assertEquals('ok', $actual);
    }
}