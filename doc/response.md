# Working with response

### Methods
>  Adding header
```php
$response->addHeader('Content-Type', 'application/json');
```

> Adding many headers
```php
$response->addHeaders(['Content-Type' => 'application/json']);
```

> Getting all headers
```php
$response->getHeaders();
```

> Set status code
```php
$response->setStatusCode(200);
// or
$response->setStatusCode('200 OK');
```

> Get status code
```php
$response->getStatusCode(200);
```

> Handler response
```php
$response->setStatusCode(404)->response()->json(['error' => 'Page not found']);

// or

$response->json(['id' => 10]);
```

[previous](request.md)