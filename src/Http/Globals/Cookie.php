<?php

namespace PlugRoute\Http\Globals;

use PlugRoute\Http\Utils\ArrayUtil;

class Cookie
{
	private array $cookie;

	public function __construct()
	{
		$this->cookie = $_COOKIE;
	}

	public function get(string $key)
	{
		return $this->cookie[$key];
	}

	public function all(): array
	{
		return $this->cookie;
	}

	public function except(array $keys): array
	{
		return ArrayUtil::except($this->cookie, $keys);
	}

	public function only(array $keys): array
	{
		return ArrayUtil::only($this->cookie, $keys);
	}

	public function has(string $key): bool
	{
		return !empty($this->cookie[$key]);
	}

	public function add(string $key, $value, $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false): void
	{
		$this->cookie[$key] = $value;

		$this->set($key, $value, $expire, $path, $domain, $secure, $httponly);
	}

	public function remove(string $key): Cookie
	{
		unset($this->cookie[$key]);

        unset($_COOKIE[$key]);

        return $this;
	}

	private function set(string $key, $value, $expire, $path, $domain, $secure, $httponly)
	{
		return setcookie($key, $value, $expire, $path, $domain, $secure, $httponly);
	}
}