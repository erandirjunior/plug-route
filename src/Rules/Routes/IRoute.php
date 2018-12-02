<?php

namespace PlugRoute\Rules\Routes;

interface IRoute
{
	public function execute($route, $url);
}