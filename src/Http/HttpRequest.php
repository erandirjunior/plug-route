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
        $request = new Request();
    	$this->route = $route;
    	$this->urlBody = $request->get();
    	$this->body = [];
        $requestService = $request->getRequisitionBody($this->getMethod());

        if ($requestService) {
            $this->body = RequestHelper::returnArrayFormated($this->body, $requestService);
        }
    }

    public function setUrlParameter($urlBody = null)
    {
        if (!is_null($urlBody)) {
            $this->urlBody = RequestHelper::returnArrayFormated($this->urlBody, $urlBody);
        }
    }

    public function get()
    {
        return $this->urlBody;
    }

    public function getWith($parameter)
    {
        return $this->urlBody[$parameter];
    }

    public function query()
    {
        return $_GET;
    }

    public function queryWith($parameter)
    {
        return $_GET[$parameter];
    }

	public function all()
	{
        return $this->body;
	}

	public function input($index) {
        return $this->body[$index];
    }

	public function setBodyParameter(array $body)
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
		$this->kill();
	}

	public function redirect($path)
	{
		header("Location: {$path}");
        $this->kill();
	}

	private function kill()
    {
        die;
    }
}