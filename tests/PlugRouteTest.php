<?php

namespace PlugRoute\Test;

use function foo\func;
use PHPUnit\Framework\TestCase;
use PlugRoute\PlugRoute;

final class PlugRouteTest extends TestCase
{
	private $expected = [
		'GET' => [
			0 => [
				'route' => '/test', 'callback' => 'Namespace\MyClass@method', 'name' => null, 'middleware' => [
					'FirstMiddleware', 'SecondMiddleware',
				],
			]
		],
		'POST' => [
			0 => [
				'route' => '/test', 'callback' => 'Namespace\MyClass@method', 'name' => null, 'middleware' => [
					'FirstMiddleware', 'SecondMiddleware',
				],
			]
		],
		'PUT' => [],
		'DELETE' => [],
		'PATCH' => [],
		'OPTIONS' => []
	];

	private $simpleTest = [
		'GET' => [
			0 => [
				'route' => '/',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'POST' => [
			0 => [
				'route' => '/',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'PUT' => [
			0 => [
				'route' => '/',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'DELETE' => [
			0 => [
				'route' => '/',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'PATCH' => [
			0 => [
				'route' => '/',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'OPTIONS' => [
			0 => [
				'route' => '/',
				'callback' => 'Class@method',
				'name' => null,
				'middleware' => [],
			]
		],
	];

	private $any = [
		'GET' => [
			0 => [
				'route' => '/',
				'callback' => 'Namespace@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'POST' => [
			0 => [
				'route' => '/',
				'callback' => 'Namespace@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'PUT' => [
			0 => [
				'route' => '/',
				'callback' => 'Namespace@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'DELETE' => [
			0 => [
				'route' => '/',
				'callback' => 'Namespace@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'PATCH' => [
			0 => [
				'route' => '/',
				'callback' => 'Namespace@method',
				'name' => null,
				'middleware' => [],
			]
		],
		'OPTIONS' => [
			0 => [
				'route' => '/',
				'callback' => 'Namespace@method',
				'name' => null,
				'middleware' => [],
			]
		],
	];

    private $instance;

    public function setUp()
    {
        $this->instance = new PlugRoute();
    }

    public function testRoutes()
    {
        $this->instance->get('/', 'Class@method');
        $this->instance->post('/', 'Class@method');
        $this->instance->put('/', 'Class@method');
        $this->instance->patch('/', 'Class@method');
        $this->instance->delete('/', 'Class@method');
        $this->instance->options('/', 'Class@method');

        $this->assertEquals($this->simpleTest, $this->instance->getRoutes());
    }

    public function testRouteGroup()
    {
        $this->instance->group(['prefix' => 'home'], function ($route) {
            $route->get('/test', 'Namespace@method');
            $route->post('/test', 'Namespace@method');
        });

        $expected = [
            'GET' => [
                0 => [
                    'route' => 'home/test',
                    'callback' => 'Namespace@method',
                    'name' => null,
                    'middleware' => [],
                ]
            ],
            'POST' => [
                0 => [
                    'route' => 'home/test',
                    'callback' => 'Namespace@method',
                    'name' => null,
                    'middleware' => [],
                ]
            ],
            'PUT' => [],
            'DELETE' => [],
            'PATCH' => [],
            'OPTIONS' => []
        ];

        $this->assertEquals($expected, $this->instance->getRoutes());
    }

    public function testAnyRoute()
    {
        $this->instance->any('/', 'Namespace@method');

        $this->assertEquals($this->any, $this->instance->getRoutes());
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


		$this->assertEquals($this->instance->getRoutes(), $this->expected);
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

		$this->assertEquals($this->instance->getRoutes(), $this->expected);
    }

	public function testErrorRoute()
	{
		$this->instance->error('MyClass@method');

		$this->assertEquals($this->instance->getErrorRoute(), ['callback' => 'MyClass@method']);
    }

	public function testErrorRouteDeprecated()
	{
		$this->instance->setRouteError('MyClass@method');

		$this->assertEquals($this->instance->getErrorRoute(), ['callback' => 'MyClass@method']);
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
		$this->instance->match($headers, '/', 'Class@method');

		$this->assertEquals($this->simpleTest, $this->instance->getRoutes());
	}

	public function testNamespace()
	{
		$content = $this->instance;
		$this->instance->namespace('Namespace', function($content) {
			$content->get('/', '\MyClass@myMethod');
		});

		$expected = [
			'GET' => [
				0 => [
					'route' => '/',
					'callback' => 'Namespace\MyClass@myMethod',
					'name' => null,
					'middleware' => [],
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