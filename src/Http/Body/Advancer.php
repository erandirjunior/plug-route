<?php

namespace PlugRoute\Http\Body;

interface Advancer
{
	public function next(Handler $handler);
}