<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\Http\Globals\File;

class FileGlobalTest extends TestCase
{
	private File $instance;

    private array $data;

	protected function setUp(): void
	{
		$this->data = [
			'name' => 'Erandir Junior',
			'age' => 23,
			'email' => 'aefs12junior@gmail.com'
		];
		$this->instance = new \PlugRoute\Test\Mock\File();
	}

    public function testGet()
    {
        self::assertEquals($this->data, $this->instance->get('test'));
    }

	public function testAll()
	{
		$expected = ['test' => $this->data];
		self::assertEquals($expected, $this->instance->all());
	}

    public function testExcept()
    {
        self::assertEquals([], $this->instance->except(['test']));
    }

	public function testOnly()
	{
        $expected = ['test' => $this->data];
		self::assertEquals($expected, $this->instance->only(['test']));
	}

	public function testHas()
	{
		self::assertEquals(true, $this->instance->has('test'));
	}

	public function testRemove()
	{
		self::assertEquals([], $this->instance->remove('test')->all());
	}
}