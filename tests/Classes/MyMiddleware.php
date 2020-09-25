<?php

namespace PlugRoute\Test\Classes;

use PlugRoute\Http\Request;
use PlugRoute\Middleware\PlugRouteMiddleware;

class MyMiddleware implements PlugRouteMiddleware
{
	public function handler(Request $request)
	{
		return $request;
	}
}