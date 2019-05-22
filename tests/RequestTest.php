<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\Test\Classes\Request;
use PlugRoute\Test\Classes\RequestCreator;

class RequestTest extends TestCase
{
	private $instance;

	public function setUp()
	{
		$this->instance = RequestCreator::create();
	}

	public function testParameters()
	{
		$this->instance->setParameter('key', 'value');

		self::assertEquals(['key' => 'value'], $this->instance->parameters());
	}

	public function testRouteNamed()
	{
		$this->instance->setRouteNamed(['key' => 'value']);

		self::assertEquals(['key' => 'value'], $this->instance->getRouteNamed());
	}

	/**
	 * @testQueryExcept
	 * @runInSeparateProcess
	 **/
	public function testRedirectRouteNamed()
	{
		$this->instance->setRouteNamed(['github' => 'https://github.com/erandirjunior/plug-route']);

		$this->instance->redirectToRoute('github');

		$this->assertContains(
			'Location: https://github.com/erandirjunior/plug-route', ['Location: https://github.com/erandirjunior/plug-route']
		);
	}

	/**
	 * @testQueryExcept
	 * @runInSeparateProcess
	 **/
	public function testRedirectRouteNamedException()
	{
		$this->expectException(\Exception::class);

		$this->instance->setRouteNamed(['github' => 'https://github.com/erandirjunior/plug-route']);

		$this->instance->redirectToRoute('page');
	}
}