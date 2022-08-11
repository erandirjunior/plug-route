<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

require_once 'OtherMiddleware.php';

use \PlugRoute\Http\Request;
use PlugRoute\Http\Response;
use PlugRoute\PlugRoute;
use PlugRoute\RouteType;

/**** CORS ****/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
/**** CORS ****/

// If you are working without virtualhost modify the file .htaccess on line 49, setting the path correct.

$route = new PlugRoute();

$route->fallback()
    ->callback(function () {
        return 'Page not found!';
    });

$route->get('/')
    ->callback(function() {
        echo "Basic route";
    });

$route->get('/people/{id:\d+}', function(Request $request) {
    echo "ID iss: {$request->parameter('id')}";
});

$route->get('/optional/{id?}')
    ->callback(function(MyRequest $request) {
        echo "Parameter if sent is: {$request->parameter('id')}";
    });

$route->post('/people')
    ->callback(function() {
        echo "Post route";
    });

$route->put('/people/{id}')
    ->callback(function(string $id) {
        echo "Put route, id: {$id}";
    })
    ->rule('id', '\d+');

$route->delete('/people/{id}')
    ->callback(function() {
        echo "Delete route";
    })
    ->rule('id', '\d+');

$route->patch('/people/{id}')
    ->callback(function() {
        echo "Patch route";
    })
    ->rule('id', '\d+');

$route->options('/people/{id}')
    ->callback(function() {
        echo "Options route";
    })
    ->rule('id', '\d+');

$route->match('/prodcuts', RouteType::POST, RouteType::POST, 'options')
    ->callback(function() {
        echo "Match route";
    });

$route->redirect('/test/redirect', '/');

$route
    ->middleware('MidlewareOne', 'MidlewareTwo')
    ->middleware(OtherMiddleware::class)
    ->prefix('/site', '/system')
    ->group(function (PlugRoute $route) {
        $route->get('/ti')
        ->callback(function(Response $response, Request $request) {
            echo $response->json(['departament' => 'IT Departament']);
        })
        ->name('ti');

        $route->get('/tecnology')
        ->callback(function(Request $request) {
            $request->redirectToRoute('ti');

        // If you use this library without name a route, without virtualhost or php server built-in
        // use the redirect method
        // $request->redirect('http://localhost/plug-route/examples/department/it');
        });
    });

$route->get('/cars')
    ->controller('\NAMESPACE\YOUR_CLASS', 'method');

$route->fromJsonFile('./route.json');

$route->run();