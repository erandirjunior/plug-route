<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\PlugRoute;
use PlugRoute\RouteContainer;
use PlugRoute\Test\Classes\Request;

final class RouteContainerTest extends TestCase
{
	private $complicatedExpectedResponse = [
		'GET' => [
			0 => [
				'route' => '/test',
                'callback' => 'Namespace\MyClass@method',
                'name' => null,
                'middleware' => [
					'FirstMiddleware', 'SecondMiddleware',
				],
			]
		],
		'POST' => [
			0 => [
				'route' => '/test',
                'callback' => 'Namespace\MyClass@method',
                'name' => null,
                'middleware' => [
					'FirstMiddleware', 'SecondMiddleware',
				],
			]
		],
		'PUT' => [],
		'DELETE' => [],
		'PATCH' => [],
		'OPTIONS' => []
	];

	private $simpleExpectedResponse = [
		'GET' => [
			0 => [
				'route' => '/cars',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'POST' => [
			0 => [
				'route' => '/cars',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'PUT' => [
			0 => [
				'route' => '/cars',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'DELETE' => [
			0 => [
				'route' => '/cars',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'PATCH' => [
			0 => [
				'route' => '/cars',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'OPTIONS' => [
			0 => [
				'route' => '/cars',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
	];

    private $instance;

    public function setUp()
    {
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
        $this->instance->get('/test', 'Namespace\MyClass@method')->middleware(['FirstMiddleware', 'SecondMiddleware']);
        $this->instance->post('/test', 'Namespace\MyClass@method')->middleware(['FirstMiddleware', 'SecondMiddleware']);


        $this->assertEquals($this->complicatedExpectedResponse, $this->instance->getRoutes());
    }

    public function testGroupWithMiddlewareAndNamespace()
    {
        $head = [
            'namespace' => 'Namespace',
            'middleware' => [
                'FirstMiddleware',
                'SecondMiddleware',
            ]
        ];
        $this->instance->group($head, function ($route) {
            $route->get('/test', '\MyClass@method');
            $route->post('/test', '\MyClass@method');
        });

        $this->assertEquals($this->instance->getRoutes(), $this->complicatedExpectedResponse);
    }

    public function testErrorRoute()
    {
        $this->instance->notFound('MyClass@method');

        $this->assertEquals($this->instance->getNotFound(), ['callback' => 'MyClass@method']);
    }

    public function testErrorRouteDeprecated()
    {
        $this->instance->notFound('MyClass@method');

        $this->assertEquals(['callback' => 'MyClass@method'], $this->instance->getNotFound());
    }

    public function testDuplicateRoute()
    {
        $this->instance->get('/', 'Class@method');
        $this->instance->get('/', 'MyClass@show');

        $expected = [
            'GET' => [
                0 => [
                    'route' => '/',
                    'callback' => 'MyClass@show',
                    'name' => null,
                    'middleware' => [],
                ]
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
        $this->instance->namespace('Namespace\\', function ($route) {
            $route->group(['middleware' => ['FirstMiddleware', 'SecondMiddleware']], function($route) {
                $route->get('/test', 'MyClass@method');
                $route->post('/test', 'MyClass@method');
            });
        });

        $this->assertEquals($this->complicatedExpectedResponse, $this->instance->getRoutes());
    }
}