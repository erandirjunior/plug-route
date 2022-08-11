<?php

namespace PlugRoute\Http\Globals;

use PlugRoute\Http\Utils\ArrayUtil;

class Session
{
	private array $session;

	public function __construct()
	{
		$this->session = $_SESSION ?? [];
	}

	public function get(string $key)
	{
		return $this->session[$key];
	}

	public function all(): array
	{
		return $this->session;
	}

	public function except(array $keys): array
	{
		return ArrayUtil::except($this->session, $keys);
	}

	public function only(array $keys): array
	{
		return ArrayUtil::only($this->session, $keys);
	}

	public function has(string $key): bool
	{
		return !empty($this->session[$key]);
	}

	public function add($key, $value): void
	{
		$this->session[$key] = $value;

		$this->set($key, $value);
	}

	public function remove(string $key): Session
	{
		unset($this->session[$key]);

        unset($_SESSION[$key]);

        return $this;
	}

    private function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }
}