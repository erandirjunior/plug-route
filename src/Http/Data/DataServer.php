<?php

namespace PlugRoute\Http\Data;

trait DataServer
{
	public function getUrl()
	{
		$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		return !empty($_SERVER['REDIRECT_BASE']) ? str_replace($_SERVER['REDIRECT_BASE'], '', $url) : $url;
	}

	public function getMethod()
	{
		return parse_url($_SERVER['REQUEST_METHOD'], PHP_URL_PATH);
	}

	public function getContentType()
	{
		if (!empty($_SERVER['CONTENT_TYPE'])) {
			return $_SERVER['CONTENT_TYPE'];
		}

		$headers = headers_list();
		foreach ($headers as $header) {
			if(stripos($header,'Content-Type') !== false) {
				$headerParts = explode(':',$header);
				return trim($headerParts[1]);
			}
		}
	}
}