<?php

namespace PlugRoute\Http;

use PlugRoute\Helpers\PlugHelper;

class HttpRequest
{
	private $body;

	public function getBody($index = null)
	{
		if (is_null($index)) {
			return $this->body;
		}

		return $this->body[$index];
	}

	public function getFiles()
	{
		return $_FILES;
	}

	public function getMethod()
	{
		return PlugHelper::getTypeRequest();
	}

	public function getHeaders()
	{

	}

	private function getRequest() {
		$typeRequest = $this->getMethod();

		switch ($typeRequest) {
			case 'GET' :
				break;
			case 'POST' :
				break;
			case 'PUT' :
				break;
			case 'DELETE' :
				break;
			case 'PATCH' :
				break;
		}
	}
}