<?php

class Auth implements \PlugRoute\Middleware\PlugRouteMiddleware
{
	public function handle(\PlugRoute\Http\Request $request): \PlugRoute\Http\Request
	{
		return $request;
	}
}