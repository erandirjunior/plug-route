<?php
/**
 * Created by PhpStorm.
 * User: erandir
 * Date: 22/10/18
 * Time: 19:25
 */

namespace PlugRoute\Http;


class HttpResponse
{
	private $header;

	public function __construct()
	{
		$this->header['statusCode'] = 200;
		$this->header['contentType'] = 'application/json';
	}

	public function setStatus($statusCode)
	{
		$this->header['statusCode'] = $statusCode;
	}

	public function setContentType($contentType)
	{
		$this->header['contentType'] = $contentType;
	}

	public function responseAsJson($data)
	{
		header("Content-type: {$this->header['contentType']}");
		header("HTTP/1.0 {$this->header['statusCode']}");

		return json_encode($data);
	}
}