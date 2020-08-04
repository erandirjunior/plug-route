<?php

namespace PlugRoute\Test\Classes;

class Home
{
	public function __construct()
	{
	}

	public function test(\PlugRoute\Http\Request $request)
	{
		return $request->parameter('test');
	}
}