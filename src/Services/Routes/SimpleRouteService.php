<?php

namespace PlugRoute\Services\Routes;

use PlugRoute\Helpers\ValidateHelper;
use PlugRoute\Services\CallbackService;

class SimpleRouteService
{
	private $callback;

	public function __construct()
	{
		$this->callback = new CallbackService();
	}

	public function execute($route, $url)
    {
		if (ValidateHelper::isEqual($route['route'], $url)) {
			return $this->callback->handleCallback($route);
		}
    }
}