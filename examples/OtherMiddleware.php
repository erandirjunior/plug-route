<?php

class OtherMiddleware implements \PlugRoute\Middleware\PlugRouteMiddleware
{
	public function handle(\PlugRoute\Http\Request $request): \PlugRoute\Http\Request
	{
		$request->add('middleware', 'settting value');
		return $request;
	}
}