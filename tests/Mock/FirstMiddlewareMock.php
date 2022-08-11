<?php

namespace PlugRoute\Test\Mock;

use PlugRoute\Http\Request;
use PlugRoute\Middleware\PlugRouteMiddleware;

class FirstMiddlewareMock implements PlugRouteMiddleware
{
    public function handler(Request $request)
    {
        $request->addParameter('firstMiddleware', true);
    }
}