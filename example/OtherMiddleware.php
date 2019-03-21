<?php

class OtherMiddleware implements \PlugRoute\Middleware\PlugRouteMiddleware
{
	public function handle(\PlugRoute\Http\Request $request): \PlugRoute\Http\Request
	{
		$request->setBody(['middleware' => 'settting value']);
		return $request;
	}
}