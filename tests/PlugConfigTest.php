<?php

require_once '../vendor/autoload.php';
require_once 'Test.php';

use \PHPUnit\Framework\TestCase;
use \PlugRoute\PlugConfig;

class PlugConfigTest extends TestCase
{
    private $config;

    private $routes = [
        ['route' => 'home/test', 'callback' => 'Example@teste',
            'type' => 'GET'],
        ['route' => 'home/test', 'callback' => 'Test@teste',
            'type' => 'POST']
    ];

    public function __construct()
    {
        parent::__construct();
        $this->config = new PlugConfig($this->routes);
    }

    public function testRoute()
    {
        $expected = [
            ['route' => 'home/test', 'callback' => 'Example@teste',
                'type' => 'GET'],
            ['route' => 'home/test', 'callback' => 'Test@teste',
                'type' => 'POST']
        ];

        $this->assertEquals($expected, $this->config->getRoutes());
    }

    public function testClassError()
    {
        $_SERVER['REQUEST_URI'] = 'home/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $expected = "Error: class don't exist.";

        $this->assertEquals($expected, $this->config->main());
    }

    public function testMethodError()
    {
        $_SERVER['REQUEST_URI'] = 'home/test';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $expected = "Error: method don't exist.";

        $this->assertEquals($expected, $this->config->main());
    }

    public function testRouteDontExist()
    {
        $_SERVER['REQUEST_URI'] = 'example';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $expected = "Error: route don't exist";

        $this->assertEquals($expected, $this->config->main());
    }
}