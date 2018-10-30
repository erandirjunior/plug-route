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
				return $this->getData();
		}
	}

	private function getData()
	{
		switch (RequestHelper::getContentType()) {
			case 'application/x-www-form-urlencoded':
			    return RequestHelper::getBodyFormData();
				break;
			case 'application/json':
			    return RequestHelper::getBodyDecode();
				break;
			default:
			    RequestHelper::getBodyFormUrlEncoded();
				break;
		}
	}
}