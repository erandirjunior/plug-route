<?php

namespace PlugRoute\Http;

use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Rules\Http\Request;

class HttpRequest
{
	private $body;

	private $urlBody;

	private $route;

	public function __construct($route)
    {
    	$this->route = $route;
        $requestService = (new Request())->getRequisitionBody($this->getMethod());
        $this->body = RequestHelper::returnArrayFormated($this->urlBody, $requestService);
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
        return $this->body;
	}

	public function getBodyWith($index) {
        return $this->body[$index];
    }

	public function setBody(array $body)
	{
		$this->body = RequestHelper::returnArrayFormated($this->body, $body);
    }

	public function getUploadFiles()
	{
		return $_FILES;
	}

	public function getMethod()
	{
		return RequestHelper::getTypeRequest();
	}

	public function redirectWithName($name)
	{
		if (empty($this->route[$name])) {
			throw new \Exception("Name wasn't defined.");
		}

		header("Location: {$this->route[$name]}");
		die;
	}

	public function redirect($path)
	{
		header("Location: {$path}");
		die;
	}
}