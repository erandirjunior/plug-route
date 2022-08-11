<?php

namespace PlugRoute\Test\Mock;

use PlugRoute\Http\Request;
use PlugRoute\Middleware\PlugRouteMiddleware;

class SecondMiddlewareMock implements PlugRouteMiddleware
{
    public function handler(Request $request)
    {
        $request->addParameter('secondMiddleware', true);
    }
}