<?php

namespace PlugRoute\Helpers;

class RouteHelper
{
	public static function removeCaractersOfString($str, array $caracters)
	{
		return str_replace($caracters, '', $str);
    }
}