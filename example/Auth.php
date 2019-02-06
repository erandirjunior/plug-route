<?php

class Auth implements \PlugRoute\Middleware\PlugRouteMiddleware
{
	public function handle($request): \PlugRoute\Http\Request
	{
		var_dump($request->all());
		return $request;
	}
}