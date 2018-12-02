<?php

namespace PlugRoute\Rules\Routes;

use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Rules\Callback\Callback;

class SimpleRouteService implements IRoute
{
	private $callback;

	public function __construct()
	{
		$this->callback = new Callback();
	}

	public function execute($route, $url)
    {
		if (ValidateHelper::isEqual($route['route'], $url)) {
			return $this->callback->handleCallback($route);
		}

		return ManagerRouteService::$accountUrlNotFound++;
    }
}