<?php

namespace PlugRoute\Http\Globals;

use PlugRoute\Http\Utils\ArrayUtil;

class Server
{
	private array $server;

	public function __construct()
	{
		$this->server = $_SERVER;
	}

	private function getHeadersFromHeadersList()
	{
		return headers_list();
	}

	protected function clearHeadersFromHeadersList($headers, $needle)
	{
		foreach ($headers as $header) {
			if(stripos($header,$needle) !== false) {
				$headerParts = explode(':',$header);
				return trim($headerParts[1]);
			}
		}
	}

	private function getContentTypeFromHeadersList($needle)
	{
		$contentType = $this->getHeadersFromHeadersList();
		return $this->clearHeadersFromHeadersList($contentType, $needle);
	}

    public function contentTypeIs(string $type): bool
    {
        return strpos($this->getContentType(), $type) !== false;
    }

	public function getContentType(): string
	{
		$contentType = $this->getContentTypeFromHeadersList('Content-Type');
		return $this->server['CONTENT_TYPE'] ?? $contentType;
	}

	public function isMethod(string $method): bool
	{
		return $this->method() === $method;
	}

	public function method(): string
	{
		return parse_url($this->server['REQUEST_METHOD'], PHP_URL_PATH);
	}

	public function getUrl(): string
	{
		$url = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);

		if (!empty($this->server['REDIRECT_BASE'])) {
			$url = str_replace($this->server['REDIRECT_BASE'], '', $url);
		}

		return $url;
	}

	public function getContent()
	{
        if (!empty($_POST)) {
            return $_POST;
        }

		return file_get_contents("php://input");
	}

    public function get(string $key)
    {
        return $this->server[$key];
    }

    public function all(): array
    {
        return $this->server;
    }

    public function except(array $keys): array
    {
        return ArrayUtil::except($this->server, $keys);
    }

    public function only(array $keys): array
    {
        return ArrayUtil::only($this->server, $keys);
    }

    public function has(string $key): bool
    {
        return !empty($this->server[$key]);
    }

    public function add($key, $value): void
    {
        $this->server[$key] = $value;

        $this->set($key, $value);
    }

    public function remove(string $key): Server
    {
        unset($this->server[$key]);

        unset($_SERVER[$key]);

        return $this;
    }

    private function set(string $key, $value)
    {
        $_SERVER[$key] = $value;
    }
}