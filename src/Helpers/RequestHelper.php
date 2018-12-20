<?php

namespace PlugRoute\Helpers;

class RequestHelper
{
	public static function returnArrayFormated($body, $values)
	{
		foreach ($values as $k => $v) {
			$body[$k] = $v;
		}
		return $body;
    }
}