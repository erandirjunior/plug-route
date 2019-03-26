# Working Request Data

#### Url values
>Getting values dynamics
```php
$route->get('/sport/{something}', function(\PlugRoute\Http\Request $request) {
     echo $request->parameter('something');
});
```

>Getting all values dynamics
```php
$route->get('/sport/{something}/{name}', function(\PlugRoute\Http\Request $request) {
     var_dump($request->parameters());
});
```

>Getting specific query
```php
// /person?age=20
$route->get('/person', function(\PlugRoute\Http\Request $request) {
     var_dump($request->queryWith('age));
});
```

>Getting all query
```php
// /person?age=20&name=Erandir
$route->get('/person', function(\PlugRoute\Http\Request $request) {
     var_dump($request->query());
});
```

#### Body request
>Getting request body
```php
$route->post('/people', function(\PlugRoute\Http\Request $request) {
     echo $request->input('id');
});
```

>Getting all request body
```php
$route->put('/people', function(\PlugRoute\Http\Request $request) {
     var_dump($request->all());
});
```

>Setting body
```php
$route->post('/people', function(\PlugRoute\Http\Request $request) {
     $request->setBody(['id' => 10]);
});
```

#### Files
>Getting files sended
```php
$route->post('/people', function(\PlugRoute\Http\Request $request) {
     var_dump($request->files());
});
```

#### Others
>Getting type request
```php
$route->post('/people', function(\PlugRoute\Http\Request $request) {
     echo $request->getMethod();
});
```

>Redirect
```php
$route->get('/people', function(\PlugRoute\Http\Request $request) {
     $request->redirectWithName('home');
     // or
     $request->redirect('/home');
});
```

>Getting type request
```php
$route->post('/people', function(\PlugRoute\Http\Request $request) {
     echo $request->getMethod();
});
```

[previous](defining-routes.md) | [next](response.md)