<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use \PlugRoute\PlugRoute;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH");
header("Access-Control-Allow-Headers: Content-Type");

$route = new PlugRoute();

$route->get('/', function () {
    echo 'Hello World!';
});

$route->get('/sport/{something}', function ($request) {
    echo $request->getUrlBodyWith('something');
});

$route->post('/people', function ($request) {
    var_dump($request->all());
});

$route->put('/people/{id}', function ($request) {
    var_dump($request->all());
    echo $request->getUrlBodyWith('id');
});

$route->delete('/people/{id}', function ($request) {
    echo $request->getUrlBodyWith('id');
});

$route->patch('/people/{id}', function ($request) {
    echo $request->getUrlBodyWith('id');
});

$route->any('/url', function () {
   echo 'Receive type requests GET, POST, PUT, PATCH and DELETE';
});

$route->group('/news', function($route) {
    $route->get('/', function() {
        echo 'Home news';
    });

    $route->get('/{something}', function($request) {
        var_dump($request->all());
    });
});

$route->on();