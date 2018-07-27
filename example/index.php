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
   var_dump($_POST);
});

$route->group('/news', function($route) {
    $route->get('/', function() {
        echo 'news basic route';
    });

    $route->get('/{something}', function() {
        echo 'news dynamic route';
    });

    $route->get('/{something}/test/{something}', function() {
        echo 'news other dynamic route';
    });
});

$route->on();