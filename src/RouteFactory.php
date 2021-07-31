<?php

namespace PlugRoute;

use PlugRoute\Http\Request;

class RouteFactory
{
    public static function create()
    {
        return new PlugRoute(new RouteContainer(), new Request());
    }
}