# plug-route

[![Latest Stable Version](https://poser.pugx.org/erandir/plug-route/version)](https://packagist.org/packages/erandir/plug-route) [![Latest Unstable Version](https://poser.pugx.org/erandir/plug-route/v/unstable)](//packagist.org/packages/erandir/plug-route) [![License](https://poser.pugx.org/erandir/plug-route/license)](https://packagist.org/packages/erandir/plug-route)

###### Powerful library for PHP routes

> Use the system to work with GET, POST, PUT, DELETE, PATCH and OPTIONS requests.

> Work with json, form-data and x-www-form-urlencoded body requests.

> Use routes without virtualhost.

> Simple and fast.

#### <a href="https://github.com/erandirjunior/plug-route/blob/master/doc/installation.md">Complete documentation</a>

## Install
```bash
composer require erandir/plug-route
```

**Basic usage**
```php
use \PlugRoute\PlugRoute;
use \PlugRoute\Http\Request;

$route = new PlugRoute(new Request());

$route->get('/', function() {
    echo 'basic route';
});

$route->on();
```
