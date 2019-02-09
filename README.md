# plug-route

[![Latest Stable Version](https://poser.pugx.org/erandir/plug-route/version)](https://packagist.org/packages/erandir/plug-route) [![Latest Unstable Version](https://poser.pugx.org/erandir/plug-route/v/unstable)](//packagist.org/packages/erandir/plug-route) [![License](https://poser.pugx.org/erandir/plug-route/license)](https://packagist.org/packages/erandir/plug-route)

###### Powerful library for PHP routes

> Use the system to work with GET, POST, PUT, DELETE and PATCH requests.

> Work with json, form-data and x-www-form-urlencoded body requests.

> Use routes without virtualhost.

####<a href="https://github.com/erandirjunior/plug-route/blob/master/doc/installation.md">Complete documentation</a>

## Install
```bash
composer require erandir/plug-route
```

**Usage**
```php
use \PlugRoute\PlugRoute;

$route = new PlugRoute();

$route->get('/', function() {
    echo 'basic route';
});

$route->on();
```
