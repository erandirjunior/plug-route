<?php

namespace PlugRoute\Middleware;

use PlugRoute\Http\Request;

interface PlugRouteMiddleware
{
    public function handler(Request $request);
}