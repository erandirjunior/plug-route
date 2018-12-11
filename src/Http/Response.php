<?php

namespace PlugRoute\Http;


class Response
{
	private $header = [];

	private $statusCode = 200;

    public function setHeader(array $header)
    {
        foreach ($header as $k => $v) {
            $this->header[$k] = $v;
        }

        return $this;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function response()
    {
        foreach ($this->header as $k => $v) {
            header("{$k}: {$v}");
        }

        header("HTTP/1.0 {$this->statusCode}");

        return $this;
    }

	public function json($data)
	{
		header("Content-type: application/json");

		return json_encode($data);
	}
}