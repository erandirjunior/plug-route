<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\Http\Response;

class ResponseTest extends TestCase
{
	private Response $instance;

	protected function setUp(): void
	{
		$this->instance = new Response();
	}

	public function testStatusCode()
	{
		self::assertEquals(200, $this->instance->getStatusCode());
		$this->instance->setStatusCode(500);
		self::assertEquals(500, $this->instance->getStatusCode());
	}

	public function testHeaders()
	{
		$headers = [
			'Cache-Control: no-cache',
			'Pragma: no-cache',
		];

		$this->instance->addHeaders($headers);
        $this->instance->addHeader('Accept-Language', 'pt-BR');

		self::assertEquals([...$headers, 2 => 'Accept-Language: pt-BR'], $this->instance->getHeaders());
	}

	/**
     * @runInSeparateProcess
	 **/
	public function testResponseJson()
	{
		$headers = [
			'Cache-Control: no-cache',
			'Pragma: no-cache',
		];

		$response = $this->instance
		->addHeaders($headers)
		->response()
		->json(['test' => 'myTest']);

		self::assertEquals('{"test":"myTest"}', $response);
	}
}