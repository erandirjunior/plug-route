<?php

use \PlugRoute\Middleware\PlugRouteMiddleware;
use \PlugRoute\Http\Request;

class OtherMiddleware implements PlugRouteMiddleware
{
	public function handler(Request $request)
	{
		$request->add('middleware', 'settting value');
	}
}