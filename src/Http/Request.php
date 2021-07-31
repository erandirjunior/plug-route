<?php

namespace PlugRoute\Http;

class Request extends \PlugHttp\Request
{
	private array $parameter;

	private array $routeNamed;

	public function __construct()
    {
        parent::__construct();
        $this->parameter = [];
        $this->routeNamed = [];
    }

    public function parameter($key)
	{
		return $this->parameter[$key];
	}

	public function parameters(): array
	{
		return $this->parameter;
	}

	public function setParameter($key, $value): Request
	{
		$this->parameter[$key] = $value;

		return $this;
	}

	public function redirectToRoute(string $name, int $code = 301)
	{
		if (empty($this->routeNamed[$name])) {
			throw new \Exception("Name wasn't defined.");
		}

		return $this->redirect($this->routeNamed[$name], $code);
	}

	public function setRouteNamed(array $routeNamed): Request
	{
		$this->routeNamed = $routeNamed;

		return $this;
	}

	public function getRouteNamed(): array
	{
		return $this->routeNamed;
	}
}