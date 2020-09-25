<?php

use \PlugRoute\Middleware\PlugRouteMiddleware;
use \PlugRoute\Http\Request;

class Auth implements PlugRouteMiddleware
{
	public function handler(Request $request)
	{
		// do something
	}
}