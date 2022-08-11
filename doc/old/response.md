# Working with response

### Methods
>  Adding header
```php
$response->addHeader('Content-Type', 'application/json');
```

> Adding several headers
```php
$response->setHeaders(['Content-Type' => 'application/json'])->response();
```

> Set status code header
```php
$response->setStatusCode(200);
// or
$response->setStatusCode('200 OK');
```

> Return a response in json format. This method set Content-Type to application/json automatically.
```php
$response->json(['id' => 10]);
```

> Manipulating responses
```php
$response->setStatusCode(404)->response()->json(['error' => 'Page not found']);
```

[previous](request.md)