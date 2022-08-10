<?php

namespace PlugRoute\Http\Globals;

use PlugRoute\Http\Utils\ArrayUtil;

class Env
{
	private array $env;

	public function __construct()
	{
		$this->env = $_ENV;
	}

	public function get(string $key)
	{
		return $this->env[$key];
	}

	public function all(): array
	{
		return $this->env;
	}

	public function except(array $keys): array
	{
		return ArrayUtil::except($this->env, $keys);
	}

	public function only(array $keys): array
	{
		return ArrayUtil::only($this->env, $keys);
	}

	public function has(string $key): bool
	{
		return !empty($this->env[$key]);
	}

	public function add(string $key, $value): void
	{
		$this->env[$key] = $value;

		$this->set($key, $value);
	}

	public function remove(string $key): Env
	{
		unset($this->env[$key]);

		unset($_ENV[$key]);

		return $this;
	}

    private function set(string $key, $value)
    {
        $_ENV[$key] = $value;
    }
}