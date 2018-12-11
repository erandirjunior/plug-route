<?php

namespace PlugRoute\Routes;

interface IRoute
{
	public function execute($route, $url);
}