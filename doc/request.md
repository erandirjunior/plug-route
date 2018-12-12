# Working Request Data

## Getting values dynamics
```php
$route->get('/sport/{something}', function($request) {
     echo $request->parameter('something');
});
```

## Getting all values dynamics
```php
$route->get('/sport/{something}/{name}', function($request) {
     var_dump($request->parameters());
});
```

## Getting specific query
```php
// /person?age=20
$route->get('/person', function($request) {
     var_dump($request->queryWith('age));
});
```

## Getting all query
```php
// /person?age=20&name=Erandir
$route->get('/person', function($request) {
     var_dump($request->query());
});
```

## Getting request body
```php
$route->post('/people', function($request) {
     echo $request->input('id');
});
```

## Getting all request body
```php
$route->put('/people', function($request) {
     var_dump($request->all());
});
```

## Getting files sended
```php
$route->post('/people', function($request) {
     var_dump($request->files());
});
```

## Getting type request
```php
$route->post('/people', function($request) {
     echo $request->getMethod();
});
```

## Redirect
```php
$route->get('/people', function($request) {
     $request->redirectWithName('home');
     // or
     $request->redirect('/home');
});
```

## Getting type request
```php
$route->post('/people', function($request) {
     echo $request->getMethod();
});
```

## Setting body
```php
$route->post('/people', function($request) {
     $request->setBody(['id' => 10]);
});
```

[Response](response.md)