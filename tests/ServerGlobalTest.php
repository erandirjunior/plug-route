<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\Http\Globals\Server;
use PlugRoute\Test\Mock\ServerClass;

class ServerGlobalTest extends TestCase
{
	private Server $instance;

	private array $data;

	protected function setUp(): void
	{
		$this->data = [
			'CONTENT_TYPE' => 'json',
			'REQUEST_METHOD' => 'POST',
			'REQUEST_URI' => '/test',
			'REDIRECT_BASE' => '/new-test'
		];
		$this->instance = new Server();

        foreach ($this->data as $key => $value) {
            $this->instance->add($key, $value);
        }
	}

	public function testMethod()
	{
		self::assertEquals('POST', $this->instance->method());
	}

	public function testContentType()
	{
		$server = new ServerClass([]);
		self::assertEquals('json', $this->instance->getContentType());
		self::assertEquals('json', $server->getContentType());
	}

	public function testIsMethod()
	{
		self::assertEquals(true, $this->instance->isMethod('POST'));
	}

	public function testHeaders()
	{
	    $allValues = $this->instance->all();
	    $actual = [
            'CONTENT_TYPE' => $allValues['CONTENT_TYPE'],
            'REQUEST_METHOD' => $allValues['REQUEST_METHOD'],
            'REQUEST_URI' => $allValues['REQUEST_URI'],
            'REDIRECT_BASE' => $allValues['REDIRECT_BASE']
        ];
		self::assertEquals($this->data, $actual);
	}

	public function testGetUrl()
	{
		self::assertEquals($this->data['REQUEST_URI'], $this->instance->getUrl());
	}

	public function testContent()
	{
		self::assertEquals("", $this->instance->getContent());
	}

	public function testGetHeader()
	{
		self::assertEquals('json', $this->instance->get('CONTENT_TYPE'));
	}
}