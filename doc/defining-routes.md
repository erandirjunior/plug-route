## Here's a basic usage example:
```php
use \PlugRoute\PlugRoute;

$route = new PlugRoute();

$route->get('/', function() {
    echo 'basic route';
});

$route->on();
``` 

## Other types of routes
> Route Type **POST**
```php
$route->post('/', function() {
    echo 'Request Type POST';
});
```

> Route Type **PUT**
```php
$route->put('/', function() {
    echo 'Request Type PUT';
});
```

> Route Type **DELETE**
```php
$route->delete('/', function() {
    echo 'Request Type DELETE';
});
```

> Route Type **PATCH**
```php
$route->patch('/', function () {
    echo 'Request Type PATCH';
});
```

> Route Type **ANY** accept requet **GET/POST/PUT/DELETE/PATCH**
```php
$route->any('/', function() {
    echo 'Hello Request';
});
```

## Defining dynamic routes
```php
$route->get('/{something}', function() {
    echo 'Dynamic route';
});

$route->get('/{something}/test/{something}', function() {
    echo 'Other dynamic route';
});
```

## Route group
```php
$route->group('/news', function($route) {
    $route->get('/', function() {
        echo 'News basic route';
    });

    $route->get('/{something}', function() {
        echo 'News dynamic route';
    });
});
``` 
## Working Classes
```php
$route->get('/', '\Path\To\Class@method');
```

[Get request data](request.md)
