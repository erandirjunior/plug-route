<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

require_once 'Auth.php';
require_once 'OtherMiddleware.php';

use \PlugRoute\Http\Request;
use \PlugRoute\Example\{A, B, C, D, E};

/**** CORS ****/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
/**** CORS ****/

// If you are working without virtualhost modify the file .htaccess on line 49, setting the path correct.

$route          = \PlugRoute\RouteFactory::create();
$dependencies   = require_once 'dependencies.php';

$route->notFound(function() {
	echo 'Error Page';
});

$route->get('/', function() {
	echo "Basic route";
});

$route->get('/people/{id:\d+}', function(Request $request) {
	echo "ID iss: {$request->parameter('id')}";
});

$route->get('/optional/{id:?}', function(Request $request) {
	echo "Parameter sent is: {$request->parameter('id')}";
});

$route->post('/people', function() {
	echo "Post route";
});

$route->put('/people/{id:\d+}', function(int $id) {
	echo "Put route, id: ${$id}";
});

$route->delete('/people/{id:\d+}', function() {
	echo "Delete route";
});

$route->patch('/people/{id:\d+}', function() {
	echo "Patch route";
});

$route->options('/people/{id:\d+}', function() {
	echo "Options route";
});

$route->match(['GET', 'POST'], '/products', function() {
	echo "Match route";
});

$route->redirect('/test/redirect', '/');

$route->group(['prefix' => '/department', 'middlewares' => [OtherMiddleware::class]], function($route) {
	$route->get('/it', function(\PlugRoute\Http\Response $response, Request $request) {
		echo $response->json(['departament' => 'IT Departament']);
	})->name('ti');

	$route->get('/tecnology', function(Request $request) {
		$request->redirectToRoute('ti');

		// If you use this library without name a route, without virtualhost or php server built-in
		// use the redirect method
//		$request->redirect('http://localhost/plug-route/examples/department/it');
	});
});

$route->get('/cars', '\NAMESPACE\YOUR_CLASS@method');

$route->loadFromJson('./routes.json');

$route->get('/contracts', '\NAMESPACE\YOUR_CLASS@method');

$route->get('/injection', function () {
    return (new A(new B(new C())))->method(new D(new E()));
});

$route->loadFromXML('routes.xml');

$route->get('/groups', '\NAMESPACE\YOUR_CLASS@method');

$route->get('/contract/{id:\d+}/item', 'PlugRoute\Example\Dependency\MyService@apresentation');

$route->on();