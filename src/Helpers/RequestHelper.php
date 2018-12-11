<?php

namespace PlugRoute\Helpers;

class RequestHelper
{
    public static function getUrlPath()
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return !empty($_SERVER['REDIRECT_BASE']) ? str_replace($_SERVER['REDIRECT_BASE'], '', $url) : $url;
    }

    public static function getTypeRequest()
    {
        return parse_url($_SERVER['REQUEST_METHOD'], PHP_URL_PATH);
    }

    public static function getContentType()
    {
        return $_SERVER['CONTENT_TYPE'];
    }

	public static function returnArrayFormated($body, $values)
	{
		foreach ($values as $k => $v) {
			$body[$k] = $v;
		}
		return $body;
    }
}