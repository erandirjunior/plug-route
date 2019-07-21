# Starting:

#### Configuration:
> Here we will configure PlugRoute with the basic example
```php
use \PlugRoute\PlugRoute;
use \PlugRoute\RouteContainer;
use \PlugRoute\Http\RequestCreator;

$route = new PlugRoute(new RouteContainer(), RequestCreator::create());

$route->get('/', function() {
    echo 'basic route';
});

$route->on();
``` 

#### Route types
> Other route types
```php
$route->get($route, $callback);

$route->post($route, $callback);

$route->put($route, $callback);

$route->delete($route, $callback);

$route->patch($route, $callback);

$route->options($route, $callback);
```

### Working Classes
```php
$route->get('/', '\Path\To\Class@method');
```
**PlugRoute supports dependency injection**

#### Route error
> Set an action if a route was not found
```php
$route->notFound($callback);
```

#### Accept multiple HTTP verbs
> Routes that responds to multiple HTTP verbs
```php
$route->any('/', $callback);

$route->match(['GET', 'POST'],'/', $callback);
```

#### Dynamic values
> Defining dynamic routes
```php
$route->get('product/{name}', function(\PlugRoute\Http\Request $request) {
    echo $request->parameter('name');
});
```

> You can pass a regex to set the route parameter
```php
$route->get('people/{id:\d+}', function(\PlugRoute\Http\Request $request) {
    echo $request->parameter('id');
});
```

#### Redirecting
***$code*** is optional
```php
$route->redirect($from, $to, $code);
```

#### Route groups
> Route group
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

> Namespace
```php
$route->namespace('MyNamespace', function($route) {
    $route->get('/', '\Example\MyClass@method'); 
    // Final namespace: MyNamespace\Example\MyClass
});
```

> Route group with namespace
```php
$route->group(['namespace' => 'MyNamespace'], function($route) {
    $route->get('/', '\Example\MyClass@method');
});
```

#### Named Routes
> Named routes
```php
$route->get($route, $callback)->name('home');
``` 

#### Middlewares
> Implementing a simple middleware
```php
$route->get($route, $callback)->middleware(['\Namespace\YOUR_MIDDLEWARE']);
```

> Route group with middlewares
```php
$route->group(['middlewares' => [\Namespace\YOUR_MIDDLWARE::class], function($route) {
    $route->get($callback, $route);

    $route->get($callback, $route);
});
``` 

**The middlewares should implement the PlugRoute\Middleware\PlugRouteMiddleware interface and can return a Request data type** 

#### JSON Route

> Simple route
```php
$route->loadFromJson($routePath);
```

```json
{
    "routes": [
        {
            "path": "/json-test",
            "method": "GET",
            "callback": "PlugRoute\\Example\\Home@example"
        }
    ]
}
```

> Group route
```json
{
    "routes": [
        {
            "group": {
                "prefix": "/sports",
                "middlewares": [
                    "Middleware1",
                    "Middleware2"
                ],
                "routes": [
                    {
                        "path": "/xadrez",
                        "method": "GET",
                        "callback": "PlugRoute\\Example\\Home@rankingXadrez"
                    }
                ]
            }
        }
    ]
}
```

**Important: access to see the more examples [here](../examples)**

[previous](installation.md) | [next](request.md)
