<?php

namespace PlugRoute;

interface Router
{
	public function handle(string $route, string $url);

	public function getParameters();

	public function route();
}