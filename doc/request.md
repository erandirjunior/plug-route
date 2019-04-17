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

> Getting specific query
```php
// /person?age=20
$request->queryWith('age);
```

> Getting all query
```php
// /person?age=20&name=Erandir
$request->query();
```

#### Body request
> Getting request body
```php
$request->input('id');
```

> Getting all request body
```php
$request->all());
```

> Setting body
```php
$request->setBody(['id' => 10]);
```

#### Files
> Getting files sended
```php
$request->files();
```

#### Others
> Getting type request
```php
echo $request->getMethod();
```

> Redirect
```php
$request->redirect('/home');
// or
$request->redirect('/home', 301);
```

> Redirect to named route
```php
$request->redirectWithName('home');
// or
$request->redirectWithName('/home', 301);
```

> Getting type request
```php
$request->getMethod();
```

[previous](defining-routes.md) | [next](response.md)