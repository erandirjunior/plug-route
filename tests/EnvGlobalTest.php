<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\Http\Globals\Env;

class EnvGlobalTest extends TestCase
{
	private Env $instance;

    private array $data;

	protected function setUp(): void
	{
		$this->data = [
			'name' => 'Erandir Junior',
			'age' => 23,
			'email' => 'aefs12junior@gmail.com'
		];

		$this->instance = new Env();

		foreach ($this->data as $key => $value) {
            $this->instance->add($key, $value);
        }
	}

    public function testGet()
    {
        $expected = 'Erandir Junior';
        self::assertEquals($expected, $this->instance->get('name'));
    }

	public function testAll()
	{
		$expected = ['name' => 'Erandir Junior', 'age' => 23, 'email' => 'aefs12junior@gmail.com'];
		self::assertEquals($expected, $this->instance->all());
	}

    public function testExcept()
    {
        $expected = ['email' => 'aefs12junior@gmail.com'];
        self::assertEquals($expected, $this->instance->except(['name', 'age']));
    }

	public function testOnly()
	{
		$expected = ['name' => 'Erandir Junior', 'age' => 23];
		self::assertEquals($expected, $this->instance->only(['name', 'age']));
	}

	public function testHas()
	{
		self::assertEquals(true, $this->instance->has('age'));
	}

	public function testRemove()
	{
		$this->instance->remove('email');
		$expected = [
			'name' => 'Erandir Junior',
			'age' => 23,
		];
		self::assertEquals($expected, $this->instance->all());
	}
}