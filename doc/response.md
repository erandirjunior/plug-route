# Working with response

### Methods
>  Adding headers
```php
$response->setHeader(['Content-Type' => 'application/json']);
```

> Set status code header.
```php
$response->setStatusCode(200);
// or
$response->setStatusCode('200 OK');
```

> execute all header.
```php
$response->setHeader(['Content-Type' => 'application/json'])->response();
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