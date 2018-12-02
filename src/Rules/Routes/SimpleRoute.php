<?php

namespace PlugRoute\Rules\Routes;

use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Rules\Callback\Callback;

class SimpleRoute implements IRoute
{
	private $callback;

	public function __construct($name)
	{
		$this->callback = new Callback($name);
	}

	public function execute($route, $url)
    {
		if (ValidateHelper::isEqual($route['route'], $url)) {
			return $this->callback->handleCallback($route);
		}

		return ManagerRoute::$accountUrlNotFound++;
    }
}