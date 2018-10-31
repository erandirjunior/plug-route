<?php

namespace PlugRoute\Http;

use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Services\RequestService;

class HttpRequest
{
	private $body;

	private $requestService;

	public function __construct()
    {
        $this->requestService = new RequestService();
    }

    public function setBody($body)
    {
        if (!is_null($body)) {
            $this->body = $body;
        }
    }

	public function all($index = null)
	{
	    $this->getRequisitionBody();

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
		return RequestHelper::getTypeRequest();
	}

	private function getRequisitionBody() {
		switch ($this->getMethod()) {
			case 'GET' :
			    $this->body = $_GET;
				break;
			case 'POST' :
                $this->body = $_POST;
				break;
            default :
                return $this->requestService->getDataRequest();
		}
	}
}