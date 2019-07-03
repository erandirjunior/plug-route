<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\DynamicRoute;
use PlugRoute\Http\RequestCreator;
use PlugRoute\PlugRoute;
use PlugRoute\RouteContainer;
use PlugRoute\RouteManager;
use PlugRoute\SimpleRoute;
use PlugRoute\Test\Classes\MiddlewareMistake;
use PlugRoute\Test\Classes\MyMiddleware;
use PlugRoute\Test\Classes\Request;

final class PlugRouteTest extends TestCase
{
    private $instance;

    public function setUp()
    {
        $this->instance = new PlugRoute(new RouteContainer(), \PlugRoute\Test\Classes\RequestCreator::create());
    }

	/**
	 * @testQueryExcept
	 * @runInSeparateProcess
	 **/
    public function testRouteRedirect()
    {
    	$this->instance->redirect('/', 'https://github.com/erandirjunior/plug-route', 301);

    	$this->instance->on();

		$this->assertContains(
			'Location: https://github.com/erandirjunior/plug-route', ['Location: https://github.com/erandirjunior/plug-route']
		);
    }

	public function testEchoReturn()
	{
		$route = new PlugRoute(new RouteContainer() ,\PlugRoute\Test\Classes\RequestCreator::create());

		$route->get('/', function() {
			return  50;
		});

		$this->expectOutputString(50);

		$route->on();
	}

	public function testRouteWorkingClass()
	{
		$route = new PlugRoute(new RouteContainer() ,\PlugRoute\Test\Classes\RequestCreator::createDynamic());

		$route->group(['middleware' => [MyMiddleware::class]], function($route) {
			$route->get('/{test}', 'PlugRoute\Test\Classes\Home@test');
		});

		$this->expectOutputString('test');

		$route->on();
	}

	public function testClassNotFound()
	{
		$this->expectException(\Exception::class);

		$route = new PlugRoute(new RouteContainer() ,\PlugRoute\Test\Classes\RequestCreator::createDynamic());

		$route->group(['middleware' => [MyMiddleware::class]], function($route) {
			$route->get('/{test}', 'PlugRoute\Test\Classes\MistakeClass@test');
		});

		$route->on();
	}

	public function testMethodNotFound()
	{
		$this->expectException(\Exception::class);

		$route = new PlugRoute(new RouteContainer() ,\PlugRoute\Test\Classes\RequestCreator::createDynamic());

		$route->group(['middleware' => [MyMiddleware::class]], function($route) {
			$route->get('/{test}', 'PlugRoute\Test\Classes\Home@testing');
		});

		$route->on();
	}

	public function testMiddlewareException()
	{
		$this->expectException(\Exception::class);

		$route = new PlugRoute(new RouteContainer() ,\PlugRoute\Test\Classes\RequestCreator::createDynamic());

		$route->group(['middlewares' => [MiddlewareMistake::class]], function($route) {
			$route->get('/{test}', 'PlugRoute\Test\Classes\Home@test');
		});

		$route->on();
	}
}