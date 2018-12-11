<?php

namespace PlugRoute\Http;

use \Symfony\Component\HttpFoundation\Request as HttpRequest;

class Request extends HttpRequest
{
	public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
	{
		parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
	}

	public function json($assoc)
	{
		if (strrpos($this->getContentType(),'application/json') !== false) {
			return json_decode($this->getContent());
		}

		return null;
	}
}