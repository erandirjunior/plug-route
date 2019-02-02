<?php

use \PHPUnit\Framework\TestCase;
use \PlugRoute\PlugRoute;

class PlugRouteTest extends TestCase
{
    public function testRoutes()
    {
        $route = new PlugRoute();

        $route->get('/','Teste@teste');

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
		];

        $this->assertEquals($expected, $route->getRoutes());
    }

    public function testRouteGroup()
    {
        $route = new PlugRoute();

        $route->group(['prefix'=> 'home'], function($route) {
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
		];

        $this->assertEquals($expected, $route->getRoutes());
    }

    public function testAnyRoute()
    {
        $route = new PlugRoute();

        $route->any('/', 'Namespace@method');

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
		];

        $this->assertEquals($expected, $route->getRoutes());
    }
}