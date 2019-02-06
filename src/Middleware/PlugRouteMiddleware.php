<?php

namespace PlugRoute\Middleware;

use PlugRoute\Http\Request;

interface PlugRouteMiddleware
{
    public function handle($request) : Request;
}