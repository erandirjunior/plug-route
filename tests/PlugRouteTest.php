<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\PlugRoute;

final class PlugRouteTest extends TestCase
{
    private $instance;

    public function setUp()
    {
        $this->instance = new PlugRoute();
    }

    public function testRoutes()
    {
        $this->instance->get('/', 'Teste@teste');

        $expected = [
            'GET' => [
                0 => [
                    'route' => '/',
                    'callback' => 'Teste@teste',
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

        $expected = [
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

        $this->assertEquals($expected, $this->instance->getRoutes());
    }
}