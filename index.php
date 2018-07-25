<?php

require_once 'vendor/autoload.php';

use \PlugRoute\PlugRoute;

$route = new PlugRoute();

$route->get('/',function() {
    echo 'hello';
});