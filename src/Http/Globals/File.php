<?php

namespace PlugRoute\Http\Globals;

use PlugRoute\Http\Utils\ArrayUtil;

class File
{
	private array $file;

	public function __construct()
	{
		$this->file = $_FILES;
	}

	public function get(string $key): array
	{
		return $this->file[$key];
	}

	public function all(): array
	{
		return $this->file;
	}

	public function except(array $keys): array
	{
		return ArrayUtil::except($this->file, $keys);
	}

	public function only(array $keys): array
	{
		return ArrayUtil::only($this->file, $keys);
	}

	public function has(string $key): bool
	{
		return !empty($this->file[$key]);
	}

	public function remove(string $key): File
	{
		unset($this->file[$key]);

		unset($_FILES[$key]);

		return $this;
	}
}