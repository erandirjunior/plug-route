<?php

namespace PlugRoute;

class Error
{
	public static function throwException(string $message)
	{
		throw new \Exception($message);
	}
}