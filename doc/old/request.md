# Working Request Data

#### Url values
> Getting values dynamics
```php
$request->parameter('something');
```

> Getting all values dynamics
```php
$request->parameters();
```

> Redirect to named route
```php
$request->redirectWithName('home');
// or
$request->redirectWithName('/home', 301);
```

### See complete methods [HERE](https://github.com/erandirjunior/plug-http/blob/master/doc/request.md)

[previous](defining-routes.md) | [next](response.md)