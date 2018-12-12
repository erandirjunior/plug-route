<?php

class OtherMiddleware implements \PlugRoute\Middleware\PlugRouteMiddleware
{
    public function handle($request, \Closure $next): callable
    {
        $request->setBody(['middleware' => 'settting value']);
        return $next($request);
    }
}