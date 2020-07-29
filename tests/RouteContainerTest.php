<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\PlugRoute;
use PlugRoute\Route;
use PlugRoute\RouteContainer;
use PlugRoute\Test\Classes\Request;

final class RouteContainerTest extends TestCase
{
	private $complicatedExpectedResponse;

	private $simpleExpectedResponse;

    private $instance;

    public function setUp()
    {
        $this->complicatedExpectedResponse = [
            'GET' => [
                0 => new Route('/test', 'Namespace\MyClass@method', '', [
                    0 => 'PlugRoute\Test\Classes\MiddlewareExample',
                    1 => 'OtherMiddleware'
                ])
            ],
            'POST' => [
                new Route('/test/{id}', 'Namespace\MyClass@method', '', [
                    0 => 'PlugRoute\Test\Classes\MiddlewareExample',
                    1 => 'OtherMiddleware',
                ])
            ],
            'PUT' => [],
            'DELETE' => [],
            'PATCH' => [],
            'OPTIONS' => []
	    ];

        foreach (['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'] as $value) {
            $this->simpleExpectedResponse[$value][] = new Route('/cars', 'Class@method');
        }

        $this->instance = new PlugRoute(new RouteContainer(), new Request());
    }

    public function testRoutes()
    {
        $this->instance->get('/cars', 'Class@method');
        $this->instance->post('/cars', 'Class@method');
        $this->instance->put('/cars', 'Class@method');
        $this->instance->patch('/cars', 'Class@method');
        $this->instance->delete('/cars', 'Class@method');
        $this->instance->options('/cars', 'Class@method');

        $this->assertEquals($this->simpleExpectedResponse, $this->instance->getRoutes());
    }

    public function testRouteGroup()
    {
        $this->instance->group(['prefix' => '/'], function ($route) {
            $route->get('cars', 'Class@method');
            $route->post('cars', 'Class@method');
            $route->put('cars', 'Class@method');
            $route->delete('cars', 'Class@method');
            $route->options('cars', 'Class@method');
            $route->patch('cars', 'Class@method');
        });

        $this->assertEquals($this->simpleExpectedResponse, $this->instance->getRoutes());
    }

	public function testAnyRoute()
	{
		$this->instance->any('/cars', 'Class@method');

		$this->assertEquals($this->simpleExpectedResponse, $this->instance->getRoutes());
	}

	public function testRouteNamed()
	{
		$this->instance->get('/', 'Class@method')->name('home');

		$this->assertEquals($this->instance->getNamedRoute(), ['home' => '/']);
	}

	public function testMiddleware()
	{
		$middlewares = [
			'PlugRoute\Test\Classes\MiddlewareExample',
			'OtherMiddleware',
		];

		$this->instance->get('/test', 'Namespace\MyClass@method')->middleware($middlewares);
		$this->instance->post('/test/{id}', 'Namespace\MyClass@method')->middleware($middlewares);


		$this->assertEquals($this->complicatedExpectedResponse, $this->instance->getRoutes());
	}

	public function testGroupWithMiddlewareAndNamespace()
	{
		$middlewares = [
			'PlugRoute\Test\Classes\MiddlewareExample',
			'OtherMiddleware',
		];

		$head = [
			'namespace' => 'Namespace',
			'middlewares' => $middlewares
		];
		$this->instance->group($head, function ($route) {
			$route->get('/test', '\MyClass@method');
			$route->post('/test/{id}', '\MyClass@method');
		});

		$this->assertEquals($this->instance->getRoutes(), $this->complicatedExpectedResponse);
	}

	public function testErrorRoute()
	{
		$this->instance->notFound('MyClass@method');

		$this->assertEquals('MyClass@method', $this->instance->getNotFound()->getCallback());
	}

	public function testErrorRouteDeprecated()
	{
		$this->instance->notFound('MyClass@method');

		$this->assertEquals('MyClass@method', $this->instance->getNotFound()->getCallback());
	}

	public function testDuplicateRoute()
	{
		$this->instance->get('/', 'Class@method');
		$this->instance->get('/', 'MyClass@show');

		$expected = [
			'GET' => [
			    new Route('/', 'MyClass@show')
			],
			'POST' => [],
			'PUT' => [],
			'DELETE' => [],
			'PATCH' => [],
			'OPTIONS' => [],
		];

		$this->assertEquals($expected, $this->instance->getRoutes());
	}

	public function testMatch()
	{
		$headers = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'DELETE', 'OPTIONS'];
		$this->instance->match($headers, '/cars', 'Class@method');

		$this->assertEquals($this->simpleExpectedResponse, $this->instance->getRoutes());
	}

	public function testNamespace()
	{
		$middlewares = [
			'PlugRoute\Test\Classes\MiddlewareExample',
			'OtherMiddleware',
		];

		$this->instance->namespace('Namespace\\', function ($route) use ($middlewares) {
			$route->group(['middlewares' => $middlewares], function($route) {
				$route->get('/test', 'MyClass@method');
				$route->post('/test/{id}', 'MyClass@method');
			});
		});

		$this->assertEquals($this->complicatedExpectedResponse, $this->instance->getRoutes());
	}

	public function testJsonRoute()
	{
		$path = dirname(__DIR__).'/examples/routes.json';

		$this->instance->loadFromJson($path);

		$twoMiddlewares = [
            "Middleware1",
            "Middleware2"
        ];

		$threeMiddlewares = [
            0 => "Middleware1",
            1 => "Middleware2",
            2 => "MiddlewareSoccer"
        ];

		$expected = [
			'GET' => [
                new Route(
                    '/json-test',
                    'PlugRoute\Example\Home@example',
                    'json'
                ),
                new Route(
                    '/json/{anything}',
                    'PlugRoute\Example\Home@anything',
                    '',
                    $twoMiddlewares
                ),
                new Route(
                    '/sports/xadrez',
                    'PlugRoute\Example\Home@rankingXadrez',
                    '', $twoMiddlewares
                ),
                new Route(
                    '/sports/f1/ranking',
                    'PlugRoute\Example\Home@rankingF1',
                    '',
                    $twoMiddlewares
                ),
                new Route(
                    '/sports/soccer/champions-league',
                    'PlugRoute\Example\Home@rankingChampions',
                    '',
                    $threeMiddlewares
                ),
                new Route(
                    '/sports/soccer/europe-league',
                    'PlugRoute\Example\Home@rankingEurope',
                    '',
                    $threeMiddlewares
                )
			],
			'POST' => [],
			'PUT' => [],
			'DELETE' => [],
			'PATCH' => [],
			'OPTIONS' => []
		];

		$this->assertEquals($expected, $this->instance->getRoutes());
	}

	public function testXMLRoutes()
	{
		$path = dirname(__DIR__).'/examples/routes.xml';

		$this->instance->loadFromXML($path);

		$expected = [
			'GET' => [
                new Route(
                    '/xml',
                    'PlugRoute\Example\Home@example',
                    'xml',
                    ['OtherMiddleware']
                ),
                new Route(
                    '/sports/boxe',
                    'PlugRoute\Example\Home@boxe',
                    '',
                    ['OtherMiddleware']
                ),
                new Route(
                    '/sports/olympics/golf',
                    'PlugRoute\Example\Home@golf',
                    '',
                    ['OtherMiddleware', 'OtherMiddleware']
                ),
                new Route(
                    '/sports/olympics/judo',
                    'PlugRoute\Example\Home@judo',
                    '',
                    ['OtherMiddleware', 'OtherMiddleware']
                )
			],
			'POST' => [],
			'PUT' => [],
			'DELETE' => [],
			'PATCH' => [],
			'OPTIONS' => []
		];

		$this->assertEquals($expected, $this->instance->getRoutes());
	}
}