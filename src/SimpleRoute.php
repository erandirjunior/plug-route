<?php

namespace PlugRoute;

class SimpleRoute implements Router
{
	private $route;

	public function handle(string $route, string $url)
	{
		$this->route = $route;

		return $this;
	}

	public function getParameters()
	{
		return [];
	}

	public function route()
	{
		$this->route;
	}
}