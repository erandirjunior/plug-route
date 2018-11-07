<?php

namespace PlugRoute\Http;

use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Services\RequestService;

class HttpRequest
{
	private $body;

	private $urlBody;

	private $requestService;

	public function __construct()
    {
        $this->requestService = new \PlugRoute\Services\Http\RequestService();
    }

    public function setUrlBody($urlBody = null)
    {
        if (!is_null($urlBody)) {
            $this->urlBody = RequestHelper::returnArrayFormated($this->urlBody, $urlBody);
        }
    }

    public function getUrlBodyAll()
    {
        return $this->urlBody;
    }

    public function getUrlBodyWith($parameter)
    {
        return $this->urlBody[$parameter];
    }

    public function getQueryAll()
    {
        return $_GET;
    }

    public function getQueryWith($parameter)
    {
        return $_GET[$parameter];
    }

	public function all()
	{
	    $this->getRequisitionBody();
        return $this->body;
	}

	public function getBodyWith($index) {
        return $this->body[$index];
    }

	public function getUploadFiles()
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
			    $this->body =  $this->requestService->getBodyPostRequest();
				break;
            default :
                $this->body = RequestHelper::returnArrayFormated($this->body, $this->requestService->getDataRequest());
		}
	}
}