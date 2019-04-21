<?php

namespace PlugRoute\Http;

use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Http\Data\DataRequest;
use PlugRoute\Http\Data\DataServer;

class Request
{
	use DataRequest;//, DataServer;

	private $body;

	private $urlBody;

	private $route;

	public function __construct()
    {
    	$this->urlBody  = [];
    	$this->body     = [];
        $requestService = $this->getRequisitionBody($this->getMethod());

        if ($requestService) {
            $this->body = RequestHelper::returnArrayFormated($this->body, $requestService);
        }
    }

	public function setRouteName($route)
	{
		$this->route = $route;
	}

    public function setUrlParameter($urlBody = null)
    {
        if (!is_null($urlBody)) {
            $this->urlBody = RequestHelper::returnArrayFormated($this->urlBody, $urlBody);
        }
    }

    public function parameters()
    {
        return $this->urlBody;
    }

    public function parameter($parameter)
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

	public function setBody(array $body)
	{
		$this->body = RequestHelper::returnArrayFormated($this->body, $body);
    }

	public function files()
	{
		return $_FILES;
	}

	public function redirectToRoute(string $name, int $code = 301)
	{
		if (empty($this->route[$name])) {
			throw new \Exception("Name wasn't defined.");
		}

        header("HTTP/1.0 {$code}");
		header("Location: {$this->route[$name]}");
		$this->kill();
	}

	public function redirect(string $path, int $code = 301)
	{
        header("HTTP/1.0 {$code}");
		header("Location: {$path}");
        $this->kill();
	}

	private function kill()
    {
        die;
    }
}