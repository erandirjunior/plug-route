<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use \PlugRoute\PlugRoute;

class Teste {
}

/**** CORS ****/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH");
header("Access-Control-Allow-Headers: Content-Type");
/**** CORS ****/

$route = new PlugRoute();
//$route->teste();

$route->get('/', function () {
	echo 'entrou';
});//->name('home');

$route->get('/', function () {
	echo 'entrou';
})->name('asa');

$route->get('/', function () {
	echo 'entrou';
});

$route->get('/aass', function () {
	echo 'entrou';
})->name('home')->middleware(\PlugRoute\Http\Request::class);

/*$route->get('/sport/{something}', function (\PlugRoute\Http\HttpRequest $request) {
    echo $request->getUrlBodyWith('something');
    $request->redirectWithName('home');
});

$route->post('/people', function ($request) {
    var_dump($request->all());
});

$route->put('/people/{id}', function ($request, $response) {
    $id = $request->getWith('id');
    echo $response->json(['id' => $id]);
});

$route->delete('/people/{id}', function ($request) {
    echo $request->getWith('id');
});

$route->patch('/people/{id}', function ($request) {
    echo $request->getWith('id');
});

$route->any('/url', function () {
   echo 'Receive type requests GET, POST, PUT, PATCH and DELETE';
});

$route->group('/news', function($route) {
    $route->get('/', function() {
        echo 'Home news';
    })->name('news');

    $route->get('/{something}', function($request) {
        echo $request->getWith('something');
    });
});

$route->any('/url', '\NAMESPACE\YOUR_CLASS@method');*/

$route->on();