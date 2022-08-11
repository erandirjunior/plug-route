# Starting:

#### Configuration:
> Here we will configure PlugRoute with the basic examples.

```php
$route = new \PlugRoute\PlugRoute(); 

$route->get('/')
    ->callback(function() {
        echo 'basic route';
    });

$route->on();
``` 

#### Route types
> Other route types
```php
$route->get($route)->callback(function() {});

$route->post($route)->callback(function() {});

$route->put($route)->callback(function() {});

$route->delete($route)->callback(function() {});

$route->patch($route)->callback(function() {});

$route->options($route)->callback(function() {});
```

#### Working Classes
```php
$route->get('/')
    ->controller('\Namespace\Class', 'method');
```

#### Route error
> Set an action if a route was not found
```php
$route->fallback()->controller('Namespace\Class', 'method');

// or

$route->fallback()->callback(function (){});
```

#### Accept multiple HTTP verbs
> Routes that responds to multiple HTTP verbs
```php
$route->any('/')->callback(function (){});

$route->match('/', 'option', 'delete', \PlugRoute\RouteType::GET)->controller('Namespace\Class', 'method');
```

#### Dynamic parameter
> Defining dynamic routes
```php
$route->get('product/{name}')->callback(function (){});
```

#### Getting parameters
> Getting dynamic routes
```php
$route->get('product/{name}')
    ->callback(function(string $name) {
        echo "Product: ${$name}";
    });
```

```php
$route->get('product/{name}')
    ->callback(function(array $parameter) {
        var_dump($parameter);
    });
```

```php
$route->get('product/{name}')
    ->callback(function(\PlugRoute\Http\Request $request) {
        echo $request->parameter('name');
    });
```

> You can pass a regex to set the route parameter
```php
$route->get('people/{id}')
    ->callback(function() {})
    ->rule('id', '\d+');
```

#### Redirecting
***$code*** is optional
```php
$route->redirect($from, $to, $code);
```

#### Named Routes
> Named routes
```php
$route->get($route)
    ->callback(function (){})
    ->name('home');
``` 

#### Middlewares
> Adding one middleware
```php
$route->middleware('\Namespace\YOUR_MIDDLEWARE')
    ->group(function ($route) {
        $route->get('/')->callback(function (){})
    });
```
*The middlewares must implement PlugRoute\Middleware\PlugRouteMiddleware interface.*

> Adding many middlewares
```php
$route->middleware('\Namespace\Middleware')
    ->middleware('\Namespace\OtherMiddleware')
    ->group(function ($route) {
        $route->get('/')->callback(function (){});
    });

// or

$route->middleware('\Namespace\Middleware', '\Namespace\OtherMiddleware')
    ->group(function ($route) {
        $route->get('/')->callback(function (){});
    });
```

#### Namespace
> Adding one namespace
```php
$route->namespace('\Namespace')
    ->group(function ($route) {
        $route->get('/')->controller('Class', 'method');
    });
```

> Adding many namespaces
```php
$route->namespace('\NamespaceA')
    ->namespace('\NamespaceB')
    ->group(function ($route) {
        $route->get('/')->controller('Class', 'method');
    });

// or

$route->namespace('\NamespaceA', '\NamespaceB')
    ->group(function ($route) {
        $route->get('/')->controller('Class', 'method');
    });
```

#### Prefix
> Adding one prefix
```php
$route->prefix('/system')
    ->group(function ($route) {
        $route->get('/')->controller('Class', 'method');
    });
```

> Adding many prefixes
```php
$route->prefix('/system')
    ->prefix('/adm')
    ->group(function ($route) {
        $route->get('/')->controller('Class', 'method');
    });

// or

$route->prefix('/system', '/adm')
    ->group(function ($route) {
        $route->get('/')->controller('Class', 'method');
    });
```

**Important: access to see the more examples [here](../examples)**

[previous](installation.md) | [next](request.md)
