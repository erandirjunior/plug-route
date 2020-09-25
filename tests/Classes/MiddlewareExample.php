<?php

namespace PlugRoute\Test\Classes;

use PlugRoute\Http\Request;
use PlugRoute\Middleware\PlugRouteMiddleware;

class MiddlewareExample implements PlugRouteMiddleware
{
	public function handler(Request $request)
	{
		$request->add('test', 'ok');
	}
}