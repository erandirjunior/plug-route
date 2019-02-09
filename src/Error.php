<?php

namespace PlugRoute;

class Error
{
	public static function showError(string $message)
	{
		throw new \Exception($message);
	}
}