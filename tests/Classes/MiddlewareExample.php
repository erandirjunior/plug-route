<?php

namespace PlugRoute\Test\Classes;

use PlugRoute\Http\Request;
use PlugRoute\Middleware\PlugRouteMiddleware;

class MiddlewareExample implements PlugRouteMiddleware
{
	public function handle(Request $request): Request
	{
		$request->add('test', 'ok');
		return $request;
	}
}