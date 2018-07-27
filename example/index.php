<?php

require_once '../vendor/autoload.php';

use \PlugRoute\PlugRoute;

$route = new PlugRoute();

$route->get('/', function() {
    echo 'basic route';
});

$route->get('/{example}', function() {
    echo 'dynamic route';
});

$route->get('/{example}/test/{something}', function() {
    echo 'other dynamic route';
});

$route->group('/news', function($route) {
    $route->get('/', function() {
        echo 'news basic route';
    });

    $route->get('/{example}', function() {
        echo 'news dynamic route';
    });

    $route->get('/{example}/test/{something}', function() {
        echo 'news other dynamic route';
    });
});

$route->on();