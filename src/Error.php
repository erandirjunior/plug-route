<?php

namespace PlugRoute;

use Exception;

class Error
{
	public static function throwException(string $message)
	{
		throw new Exception($message);
	}
}