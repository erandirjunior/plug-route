<?php

require_once 'vendor/autoload.php';

use \PlugRoute\PlugRoute;

$route = new PlugRoute();

$route->get('/', function() {
    echo 'rota básica';
    var_dump($_GET);
    echo "<hr>";
    var_dump($_POST);
});

$route->get('/{example}', function() {
    echo 'rota dinâmica básica';
});

$route->get('/{example}/test/{e}', function() {
    echo 'rota dinâmica intermediária';
});

$route->get('/{example}/test/{e}/a', function() {
    echo 'rota dinâmica avançada';
});

$route->group('/news', function($route) {
    $route->get('/', function() {
        echo 'rota news básica';
    });

    $route->get('/{example}', function() {
        echo 'rota news dinâmica básica';
    });

    $route->get('/{example}/t/{e}', function() {
        echo 'rota news dinâmica intermediária';
    });

    $route->get('/{example}/t/{e}/a', function() {
        echo 'rota news dinâmica avançada';
    });
});


$route->on();

var_dump($route->getRoutes());