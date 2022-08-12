<?php

namespace PlugRoute\Example;

use PlugRoute\Http\Request;
use PlugRoute\Middleware\PlugRouteMiddleware;

class OtherMiddleware implements PlugRouteMiddleware
{
	public function handler(Request $request)
	{
		$request->add('middleware', 'settting value');
	}
}