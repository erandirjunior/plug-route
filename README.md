# plug-route

[![Latest Stable Version](https://poser.pugx.org/erandir/plug-route/version)](https://packagist.org/packages/erandir/plug-route)
[![License](https://poser.pugx.org/erandir/plug-route/license)](https://packagist.org/packages/erandir/plug-route)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/erandirjunior/plug-route/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
<!--[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/erandirjunior/plug-route/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/erandirjunior/plug-route/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/erandirjunior/plug-route/badges/build.png?b=master)](https://scrutinizer-ci.com/g/erandirjunior/plug-route/build-status/master)-->

###### Powerful library for PHP routes

> Use the system to work with GET, POST, PUT, DELETE, PATCH and OPTIONS requests.

> Work with json, form-data and x-www-form-urlencoded body requests.

> Use routes without virtualhost.

> Simple and fast.

#### <a href="https://github.com/erandirjunior/plug-route/blob/master/doc/installation.md">Full documentation</a>

## Install
```bash
composer require erandir/plug-route
```

**Basic usage**
```php
$route = \PlugRoute\RouteFactory::create();

$route->get('/', function() {
    echo 'basic route';
});

$route->on();
```
