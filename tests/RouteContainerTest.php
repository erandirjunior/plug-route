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
                'middlewares' => [
					0 => 'PlugRoute\Test\Classes\MiddlewareExample',
					1 => 'OtherMiddleware',
				],
			]
		],
		'POST' => [
			0 => [
				'route' => '/test',
                'callback' => 'Namespace\MyClass@method',
                'name' => null,
                'middlewares' => [
					0 => 'PlugRoute\Test\Classes\MiddlewareExample',
					1 => 'OtherMiddleware',
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
				'middlewares' => [],
			]
		],
		'POST' => [
			0 => [
				'route' => '/cars',
				'callback' => 'Class@method',
				'name' => null,
				'middlewares' => [],
			]
		],
		'PUT' => [
			0 => [
				'route' => '/cars',
				'callback' => 'Class@method',
				'name' => null,
				'middlewares' => [],
			]
		],
		'DELETE' => [
			0 => [
				'route' => '/cars',
				'callback' => 'Class@method',
				'name' => null,
				'middlewares' => [],
			]
		],
		'PATCH' => [
			0 => [
				'route' => '/cars',
				'callback' => 'Class@method',
				'name' => null,
				'middlewares' => [],
			]
		],
		'OPTIONS' => [
			0 => [
				'route' => '/cars',
				'callback' => 'Class@method',
				'name' => null,
				'middlewares' => [],
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
		$middlewares = [
			'PlugRoute\Test\Classes\MiddlewareExample',
			'OtherMiddleware',
		];

		$this->instance->get('/test', 'Namespace\MyClass@method')->middleware($middlewares);
		$this->instance->post('/test', 'Namespace\MyClass@method')->middleware($middlewares);


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
					'middlewares' => [],
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
		$middlewares = [
			'PlugRoute\Test\Classes\MiddlewareExample',
			'OtherMiddleware',
		];

		$this->instance->namespace('Namespace\\', function ($route) use ($middlewares) {
			$route->group(['middlewares' => $middlewares], function($route) {
				$route->get('/test', 'MyClass@method');
				$route->post('/test', 'MyClass@method');
			});
		});

		$this->assertEquals($this->complicatedExpectedResponse, $this->instance->getRoutes());
	}

	public function testJsonRoute()
	{
		$path = dirname(__DIR__).'/examples/routes.json';

		$this->instance->loadFromJson($path);

		$expected = [
			'GET' => [
				0 => [
					'route' => '/json-test',
					'callback' => 'PlugRoute\Example\Home@example',
					'name' => 'json',
					'middlewares' => [],
				],
				1 => [
					'route' => '/json/{anything}',
					'callback' => 'PlugRoute\Example\Home@anything',
					'name' => null,
					'middlewares' => [
						"Middleware1",
						"Middleware2"
					]
				],
				2 => [
					'route' => '/sports/xadrez',
					'callback' => 'PlugRoute\Example\Home@rankingXadrez',
					'name' => null,
					'middlewares' => [
						0 => "Middleware1",
						1 => "Middleware2"
					]
				],
				3 => [
					'route' => '/sports/f1/ranking',
					'callback' => 'PlugRoute\Example\Home@rankingF1',
					'name' => null,
					'middlewares' => [
						0 => "Middleware1",
						1 => "Middleware2"
					]
				],
				4 => [
					'route' => '/sports/soccer/champions-league',
					'callback' => 'PlugRoute\Example\Home@rankingChampions',
					'name' => null,
					'middlewares' => [
						0 => "Middleware1",
						1 => "Middleware2",
						2 => "MiddlewareSoccer"
					]
				],
				5 => [
					'route' => '/sports/soccer/europe-league',
					'callback' => 'PlugRoute\Example\Home@rankingEurope',
					'name' => null,
					'middlewares' => [
						0 => "Middleware1",
						1 => "Middleware2",
						2 => "MiddlewareSoccer"
					]
				]
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
				0 => [
					'route' => '/xml',
					'callback' => 'PlugRoute\Example\Home@example',
					'name' => 'xml',
					'middlewares' => [
						"OtherMiddleware"
					],
				],
				1 => [
					'route' => '/sports/boxe',
					'callback' => 'PlugRoute\Example\Home@boxe',
					'name' => null,
					'middlewares' => [
						"OtherMiddleware"
					]
				],
				2 => [
					'route' => '/sports/olympics/golf',
					'callback' => 'PlugRoute\Example\Home@golf',
					'name' => null,
					'middlewares' => [
						0 => "OtherMiddleware",
						1 => "OtherMiddleware"
					]
				],
				3 => [
					'route' => '/sports/olympics/judo',
					'callback' => 'PlugRoute\Example\Home@judo',
					'name' => null,
					'middlewares' => [
						0 => "OtherMiddleware",
						1 => "OtherMiddleware"
					]
				]
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