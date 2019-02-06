<?php

namespace PlugRoute\Helpers;

class PlugHelper
{
    public static function getMatchAll($route, $pattern = '{(.*?)}')
    {
        preg_match_all("/{$pattern}/", $route, $match);
        return $match[0];
    }

	public static function getMatch($route, $pattern = '{(.*?)}')
	{
		$pattern = "/{$pattern}/";
		preg_match($pattern, $route, $matches);
		return !empty($matches[1]) ? $matches[1] : null;
    }

    public static function removeEmptyValueFromArray(array $array)
    {
        return array_filter($array);
    }

    public static function getFirstValueArrayFiltered($value, $delimiter = '/')
    {
		$newArray 					= is_array($value) ? $value : self::stringToArray($value, $delimiter);
		$arrayWithoutEmptyValues 	= self::removeEmptyValueFromArray($newArray);
		return array_shift($arrayWithoutEmptyValues);
    }

	public static function stringToArray($str, $delimiter = '/')
	{
		return explode($delimiter, $str);
    }

	public static function replace($search, $replace, $subject)
	{
		return str_replace($search, $replace, $subject);
    }
}