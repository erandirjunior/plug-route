<?php
require_once dirname(__DIR__).'/vendor/autoload.php';

require_once 'Auth.php';
require_once 'OtherMiddleware.php';

use \PlugRoute\PlugRoute;

/**** CORS ****/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH");
header("Access-Control-Allow-Headers: Content-Type");
/**** CORS ****/

// If you are working without virtual host modify the file .htaccess on line 49, setting the path correct.

$route = new PlugRoute();

$route->setRouteError(function() {
    echo "The requested page does not exists.";
});

$route->get('/', function (\PlugRoute\Http\Response $response) {
	echo $response->response()->json(['values' => [10, 20]]);
});

$route->post('/people/{id:\d+}', function (\PlugRoute\Http\Request $request) {
    var_dump($request->parameters());
});

$route->put('/people/{id:\d+}', function (\PlugRoute\Http\Request $request, \PlugRoute\Http\Response $response) {
    $id = $request->parameter('id');
    echo $response->json(['id' => $id]);
});

$route->delete('/people/{id:\d+}', function (\PlugRoute\Http\Request $request) {
    echo $request->parameter('id');
});

$route->patch('/people/{id:\d+}', function (\PlugRoute\Http\Request $request) {
    echo $request->parameter('id');
});

$route->any('/url', function () {
    echo 'Receive type requests GET, POST, PUT, PATCH and DELETE';
});

$route->group(['prefix' => '/news'], function($route) {
    $route->get('/sport', function() {
        echo 'Home news';
    });
});

$route->get('/sports', function() {
    echo 'Sports';
})->name('sports');

$route->get('/sports/{something}', function(\PlugRoute\Http\Request $request) {
    $request->redirectToRoute('sports');

    // If you use this library without name a route, without virtualhost or php server built-in
    // use the redirect method
    //$request->redirect('http://localhost/plug-route/example/sports');
});

$route->group(['prefix' => '/products', 'middleware' => [OtherMiddleware::class]], function($route) {

    $route->get('/', function() {
        echo 'Home';
    })->middleware(Auth::class);

    $route->get('/{something}', function(\PlugRoute\Http\Request $request) {
        echo $request->parameter('something');
    });
});

$route->get('/cars', '\NAMESPACE\YOUR_CLASS@method');

$route->on();
