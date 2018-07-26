<?php

require_once '../vendor/autoload.php';

use \PHPUnit\Framework\TestCase;
use \PlugRoute\PlugRoute;

class PlugRouteTest extends TestCase
{
    public function testRoutes()
    {
        $route = new PlugRoute();

        $route->get('/','Teste@teste');

        $this->assertEquals([['route' => '/', 'callback' => 'Teste@teste',
            'type' => 'GET']], $route->getRoutes());
    }

    public function testRouteGroup()
    {
        $route = new PlugRoute();

        $route->group('home', function($route) {
           $route->get('/test', 'Teste@teste');
           $route->post('/test', 'Teste@teste');
        });

        $expected = [
            ['route' => 'home/test', 'callback' => 'Teste@teste',
                'type' => 'GET'],
            ['route' => 'home/test', 'callback' => 'Teste@teste',
                'type' => 'POST']
        ];

        $this->assertEquals($expected, $route->getRoutes());
    }

    public function testAnyRoute()
    {
        $route = new PlugRoute();

        $route->any('/', 'Teste@teste');

        $this->assertEquals([['route' => '/', 'callback' => 'Teste@teste',
            'type' => 'ANY']], $route->getRoutes());
    }
}