# Working with response

## Methods
> $response->setHeader(array $headers) - Receives a header array.
```php
$route->put('/people/{id}', function ($request, $response) {
    $response->setHeader(['Content-Type', 'application/json');
});
```

> $response->setStatusCode($code) - Set status code header.
```php
$route->put('/people/{id}', function ($request, $response) {
    $response->setStatusCode(200);
    // or
    $response->setStatusCode('200 OK');
});
```

> $response->response() - execute all header.
```php
$route->put('/people/{id}', function ($request, $response) {
    $response->setHeader(['Content-Type', 'application/json')->reponse();
});
```

> $response->json(array $data) -> return an array in json format. This method set Content-Type to application/json automatically.
```php
$route->put('/people/{id}', function ($request, $response) {
    echo $response->json(['id' => 10]);
});
```

## Manipulating response
```php
$route->put('/people/{id}', function ($request, $response) {
    echo $response->setStatusCode(404)->response->json(['error' => 'Page not found']);
});
```

[previous](request.md)