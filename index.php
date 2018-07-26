<?php

require_once 'vendor/autoload.php';

use \PlugRoute\PlugRoute;

$route = new PlugRoute();

$route->get('{teste}/nada/{a}', function() {
    echo 'olá';
});

$route->get('/{exemplo}', function() {
    echo 'Exemplo de funcionamento de rota dinâmica';
});

/*$route->get('/', function() {
    echo 'rota de qq tipo';
});*/

/*$route->group('/noticias', function($route) {
    $route->get('/esporte', function() {
        echo 'noticias sobre esporte';
    });

    $route->get('/tecnologia', function() {
        echo 'noticias sobre tecnologia';
    });
});*/

$route->on();

var_dump($route->getRoutes());