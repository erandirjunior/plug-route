<?php

namespace PlugRoute\Test\Classes;

class Home
{
	public function __construct(\PlugRoute\Http\Request $request)
	{
	}

	public function test(\PlugRoute\Http\Request $request)
	{
		return $request->parameter('test');
	}
}