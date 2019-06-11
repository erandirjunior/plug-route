<?php

use \PlugRoute\Middleware\PlugRouteMiddleware;
use \PlugRoute\Http\Request;

class Auth implements PlugRouteMiddleware
{
	public function handle(Request $request): Request
	{
		return $request;
	}
}