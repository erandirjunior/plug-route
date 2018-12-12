<?php

class Auth implements \PlugRoute\Middleware\PlugRouteMiddleware
{
    public function handle($request, \Closure $next): callable
    {
        var_dump($request->all());
        return $next($request);
    }
}