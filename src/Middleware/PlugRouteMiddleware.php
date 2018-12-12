<?php

namespace PlugRoute\Middleware;

interface PlugRouteMiddleware
{
    public function handle($request, \Closure $next)  : callable;
}