# Working Request Data

## Getting Body
 
>  Getting all body values as array
```php
$request->all();
```

> You can get data as dynamic class properties
```php
$request->username;
$request->name;
$request->email;
```

>  Getting all values as stdClass object
```php
$request->bodyAsObject();
```

> Getting specific body value
```php
$request->get($key);
```

> Getting all body values except
```php
$request->except([$keyOne, $keyTwo]);
```

> Getting only body values
```php
$request->only([$keyOne, $keyTwo]);
```

> Add value in body
```php
$request->add($key, $value);
```

> Remove body value
```php
$request->remove($key);
```

> Check if has value
```php
$request->has($key);
```

## Handler Get

>  Getting all query string values
```php
$request->query();
```

> Getting specific query string value
```php
$request->query($key);
```

> Getting except query string values
```php
$request->exceptQueries([$keyOne, $keyTwo]);
```

> Getting only query string values
```php
$request->onlyQueries([$keyOne, $keyTwo]);
```

> Add value
```php
$request->addQuery($key, $value);
```

> Remove value
```php
$request->removeQuery($key);
```

> Check if has value in query string
```php
$request->hasQuery($key);
```

## Manipulate File

> Getting all files
```php
$request->files();
```

> Getting specific file
```php
$request->files($key);
```

> Check if specific file was sent
```php
$request->hasFile($key);
```

> Remove specific file
```php
$request->removeFile($key);
```

> Getting only files
```php
$request->onlyFiles([$key]);
```

> Getting except files
```php
$request->exceptFiles([$key]);
```

## Manipulate Server

> Getting method
```php
$request->method();
```

> Check request method
```php
$request->isMethod($method);
```

> Getting all headers
```php
$request->headers();
```

> Getting specific header
```php
$request->headers('Content-Type');
```

> Getting url
```php
$request->getUrl();
```

> Redirecting
```php
$request->redirect($url, $code);
```
**$code is optional**

> Getting only headers
```php
$request->onlyHeaders([$key]);
```

> Getting except headers
```php
$request->exceptHeaders([$key]);
```

> Remove a header
```php
$request->removeHeader($key);
```

> Check if specific header exists
```php
$request->hasheader($key);
```

> Add new header
```php
$request->addHeader($key, $value);
```

> Getting request method
```php
$request->method();
```

## Handler Cookies

> Added new cookie
```php
$request->addCookie($key, $value, $expire, $path, $domain, $secure, $httponly);
```
***The parameters $expire, $path, $domain, $secure and $httponly, are optionals***

> Getting all cookies values
```php
$request->cookies();
```

> Getting specific cookie value
```php
$request->cookies($key);
```

> Check if specific cookie exists
```php
$request->hasCookie($key);
```

> Remove specific cookie
```php
$request->removeCookie($key);
```

> Getting only cookies
```php
$request->onlyCookies([$key]);
```

> Getting except cookies
```php
$request->exceptCookies([$key]);
```

## Manipulate Env

> Added new env
```php
$request->addEnv($key, $value);
```

> Getting all env values
```php
$request->env();
```

> Getting specific env value
```php
$request->env($key);
```

> Check if specific env exists
```php
$request->hasEnv($key);
```

> Remove specific env
```php
$request->removeEnv($key);
```

> Getting only env
```php
$request->onlyEnv([$key]);
```

> Getting except env
```php
$request->exceptEnv([$key]);
```

## Manipulate Session

> Added new session value
```php
$request->addSession($key, $value);
```

> Getting all session values
```php
$request->session();
```

> Getting specific session value
```php
$request->session($key);
```

> Check if specific session exists
```php
$request->hasSession($key);
```

> Remove specific session value
```php
$request->removeSession($key);
```

> Getting only session values
```php
$request->onlySession([$key]);
```

> Getting except session values
```php
$request->exceptSession([$key]);

#### Url values
> Getting values dynamics
```php
$request->parameter('something');
```

> Getting all values dynamics
```php
$request->parameters();
```

> Add a new parameter
```php
$request->addParameter($key, $value);
```

> Add many parameters
```php
$request->addParameters(array $data);
```

> Redirect to named route
```php
$request->redirectToRoute('home');
```

> Get all named route
```php
$request->getAllRouteNamed();
```

[previous](defining-routes.md) | [next](response.md)