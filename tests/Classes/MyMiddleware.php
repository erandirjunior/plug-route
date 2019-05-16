<?php

namespace PlugRoute\Test\Classes;

use PlugRoute\Http\Request;
use PlugRoute\Middleware\PlugRouteMiddleware;

class MyMiddleware implements PlugRouteMiddleware
{
	public function handle(Request $request): Request
	{
		return $request;
	}
}