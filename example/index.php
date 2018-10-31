<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use \PlugRoute\PlugRoute;

$route = new PlugRoute();

$route->put('/', function($request, $response) {
    //var_dump(get_class_methods($request), get_class_methods($response));
    $request->all();
});

$route->post('/', function() {
    echo 'This is a post route';
});

$route->get('/{parameterOne}/test/{parameterTwo}', function() {
    echo 'other dynamic route';
});

$route->any('/{something}/{a}', function() {
   echo 'route type any';
});

$route->any('/{something}/{a}', function() {
   echo 'route type any2';
});

$route->group('/news', function($route) {
    $route->get('/', function() {
        echo 'news basic route';
    });

    // /news/sport
    $route->get('/{something}', function($data) {
        var_dump($data); // array (size=1) 0 => string 'sport' (length=5)
    });

    $route->any('/{something}/test/{something}', function() {
        echo 'news other dynamic route';
    });
});

$route->any('/route/type/any', function() {
    echo 'hi';
});

$route->on();