<?php

use \PlugRoute\Middleware\PlugRouteMiddleware;
use \PlugRoute\Http\Request;

class OtherMiddleware implements PlugRouteMiddleware
{
	public function handle(Request $request): Request
	{
		$request->add('middleware', 'settting value');
		return $request;
	}
}