<?php

namespace PlugRoute;

use PlugRoute\Http\RequestCreator;

class RouteFactory
{
    public static function create()
    {
        return new PlugRoute(new RouteContainer(), RequestCreator::create());
    }
}