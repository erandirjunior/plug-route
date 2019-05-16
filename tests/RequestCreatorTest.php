<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\Http\RequestCreator;

class RequestCreatorTest extends TestCase
{
	private $instance;

	public function setUp()
	{
		$this->instance = RequestCreator::create();
	}

	public function testInstance()
	{
		$isTrue = $this->instance instanceof \PlugRoute\Http\Request;

		self::assertEquals(true, $isTrue);
	}
}