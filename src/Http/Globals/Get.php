<?php

namespace PlugRoute\Http\Globals;

use PlugRoute\Http\Utils\ArrayUtil;

class Get
{
	private array $get;

	public function __construct()
	{
		$this->get = $_GET;
	}

	public function get(string $key)
	{
		return $this->get[$key];
	}

	public function all(): array
	{
		return $this->get;
	}

	public function except(array $keys): array
	{
		return ArrayUtil::except($this->get, $keys);
	}

	public function only(array $keys): array
	{
		return ArrayUtil::only($this->get, $keys);
	}

	public function has(string $key): bool
	{
		return !empty($this->get[$key]);
	}

	public function add($key, $value): void
	{
		$this->get[$key] = $value;

		$this->set($key, $value);
	}

	public function remove(string $key): Get
	{
		unset($this->get[$key]);

		unset($_GET[$key]);

		return $this;
	}

    private function set(string $key, $value)
    {
        $_GET[$key] = $value;
    }
}