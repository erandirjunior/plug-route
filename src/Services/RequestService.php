<?php

namespace PlugRoute\Services;

use PlugRoute\Helpers\RequestHelper;

class RequestService
{
	public function getDataRequest()
	{
		switch (RequestHelper::getTypeRequest()) {
			case 'GET' :
				return $_GET;
				break;
			case 'POST' :
				return $_POST;
				break;
			default:
				return $this->getBodyData();
		}
	}

	private function getBodyData()
	{
		switch (RequestHelper::getContentType()) {
			case 'application/x-www-form-urlencoded':
				break;
			case 'application/json':
				break;
			default:
				break;
		}
	}
}