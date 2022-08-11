<?php

namespace PlugRoute\Http;

/**
 * Class Response
 * @package PlugRoute\Http
 */
class Response
{
    /**
     * @var array
     */
    private array $headers;

    /**
     * @var int
     */
    private int $statusCode;

    /**
     * Response constructor.
     */
    public function __construct()
    {
        $this->headers = [];
        $this->statusCode = 200;
    }

    /**
     * Added several headers.
     *
     * @param array $headers
     * @return $this
     */
    public function addHeaders(array $headers): Response
	{
		foreach ($headers as $v) {
			$this->headers[] = $v;
		}

		return $this;
	}

    /**
     * Added a header.
     *
     * @param string $header
     * @param mixed $value
     * @return $this
     */
    public function addHeader(string $header, $value): Response
	{
        $this->headers[] = $header.': '.$value;

		return $this;
	}

    /**
     * Returns the defined headers.
     * @return array
     */
    public function getHeaders(): array
	{
		return $this->headers;
	}

    /**
     * Set status code.
     *
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode): Response
	{
		$this->statusCode = $statusCode;

		return $this;
	}

    /**
     * Returns the defined status code.
     *
     * @return int
     */
    public function getStatusCode(): int
	{
		return $this->statusCode;
	}

    /**
     * Set all headers.
     *
     * @return $this
     */
    public function response(): Response
	{
		foreach ($this->headers as $k => $v) {
			header("{$v}");
		}

		header("HTTP/1.0 {$this->statusCode}");

		return $this;
	}

    /**
     * Return a json and set the header to application/json.
     *
     * @param array $data
     * @return false|string
     */
    public function json(array $data)
	{
		header("Content-type: application/json");

		return json_encode($data);
	}
}