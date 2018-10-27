<?php

require_once '../vendor/autoload.php';

use \PlugRoute\PlugRoute;

$route = new PlugRoute();

$route->get('/', function() {
    echo 'basic route';
});

$route->post('/', function() {
    echo 'This is a post route';
});

$route->get('/{something}/test/{something}', function() {
    echo 'other dynamic route';
});

$route->any('/home', function() {
   echo 'route type any';
});

$route->group('/news', function($route) {
    //var_dump($route);
    $route->get('/', function() {
        echo 'news basic route';
    });

    // /news/sport
    $route->get('/{something}', function($data) {
        var_dump($data); // array (size=1) 0 => string 'sport' (length=5)
    });

    $route->get('/{something}/test/{something}', function() {
        echo 'news other dynamic route';
    });
});

$route->any('/route/type/any', function() {
    echo 'hi';
});

$route->getRoutes();

$route->on();