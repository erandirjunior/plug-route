## Configuration:

>Here we will configure PlugRoute with the basic example
```php
use \PlugRoute\PlugRoute;

$route = new PlugRoute();

$route->get('/', function() {
    echo 'basic route';
});

$route->on();
``` 

>Working Classes
```php
$route->get('/', '\Path\To\Class@method');
```

>Defining error route
```php
$route->setRouteError($callback);
```

>Other types of routes
```php
$route->post($route, $callback);

$route->put($route, $callback);

$route->delete($route, $callback);

$route->patch($route, $callback);

$route->any($route, $callback);
```

>Defining dynamic routes
```php
$route->get('product/{name}', function($request) {
    echo $request->parameter('id');
});
```

>You can pass a regex to set the route parameter
```php
$route->get('people/{id:\d+}', function($request) {
    echo $request->parameter('id');
});
```

>Route group
```php
$route->group(['prefix' => '/news'], function($route) {
    $route->get('/', function() {
        echo 'Home page';
    });

    $route->get('/sport', function() {
        echo 'Sports page';
    });
});
``` 

>Named routes
```php
$route->get($route, $callback)->name('home');
``` 

#### Middlewares
>Implementing a simple middleware
```php
$route->get($route, $callback)->middleware(\Namespace\YOUR_MIDDLWARE::class);
``` 

>Route group with middlewares
```php
$route->group(['prefix' => '/news', 'middleware' => [\Namespace\YOUR_MIDDLWARE::class], function($route) {
    $route->get($callback, $route);

    $route->get($callback, $route);
});
``` 
**The middlewares should implement the PlugRoute\Middleware\PlugRouteMiddleware interface and can return a Request type data** 

**Important: see the more examples [here](../example)**

[previous](installation.md) | [next](request.md)
