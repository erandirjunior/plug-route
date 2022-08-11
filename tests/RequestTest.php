<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\Http\Request;

class RequestTest extends TestCase
{
	private Request $instance;

	protected function setUp(): void
	{
        $_GET = [];
        $_POST = [];
        $_COOKIE = [];
        $_ENV = [];
        $_FILES = [
            'profile' => [
                'error'    => UPLOAD_ERR_OK,
                'name'     => 'profile.png',
                'size'     => 123,
                'tmp_name' => __FILE__,
                'type'     => 'image/png'
            ],
            'background' => [
                'error'    => UPLOAD_ERR_OK,
                'name'     => 'backgroun.png',
                'size'     => 123,
                'tmp_name' => __FILE__,
                'type'     => 'image/png'
            ]
        ];
        $_REQUEST = [];
        $_SESSION = [];
		$this->instance = new Request();
	}

	public function testQuery()
	{
        $this->instance->addQuery('id', 10);
        $this->instance->addQuery('name', 'Erandir Junior');
        $this->instance->addQuery('site', 'github');
		self::assertEquals(10, $this->instance->query('id'));
		self::assertEquals(['name' => 'Erandir Junior', 'site' => 'github'], $this->instance->onlyQueries(['name', 'site']));
		self::assertEquals(['id' => '10', 'site' => 'github'], $this->instance->exceptQueries(['name']));
		self::assertEquals(['id' => '10', 'name' => 'Erandir Junior','site' => 'github'], $this->instance->query());

        $this->instance->removeQuery('site');
		self::assertEquals(false, $this->instance->hasQuery('site'));
	}

	public function testEnv()
	{
        $this->instance->addEnv('id', 10);
        $this->instance->addEnv('name', 'Erandir Junior');
        $this->instance->addEnv('site', 'github');
		self::assertEquals(10, $this->instance->env('id'));
		self::assertEquals(['name' => 'Erandir Junior', 'site' => 'github'], $this->instance->onlyEnv(['name', 'site']));
		self::assertEquals(['id' => '10', 'site' => 'github'], $this->instance->exceptEnv(['name']));
		self::assertEquals(['id' => '10', 'name' => 'Erandir Junior','site' => 'github'], $this->instance->env());

        $this->instance->removeEnv('site');
		self::assertEquals(false, $this->instance->hasEnv('site'));
	}

	public function testFile()
	{
		self::assertEquals($_FILES, $this->instance->files());
		self::assertEquals($_FILES['background'], $this->instance->files('background'));
		self::assertEquals(['profile' => $_FILES['profile']], $this->instance->onlyFiles(['profile']));
		self::assertEquals(['background' => $_FILES['background']], $this->instance->exceptFiles(['profile']));

        $this->instance->removeFile('profile');
		self::assertEquals(false, $this->instance->hasFile('profile'));
	}

    /**
     * @runInSeparateProcess
     */
	public function testCookie()
	{
        $this->instance->addCookie('id', 10);
        $this->instance->addCookie('name', 'Erandir Junior');
        $this->instance->addCookie('site', 'github');
        self::assertEquals(10, $this->instance->cookies('id'));
        self::assertEquals(['name' => 'Erandir Junior', 'site' => 'github'], $this->instance->onlyCookies(['name', 'site']));
        self::assertEquals(['id' => '10', 'site' => 'github'], $this->instance->exceptCookies(['name']));
        self::assertEquals(['id' => '10', 'name' => 'Erandir Junior','site' => 'github'], $this->instance->cookies());

        $this->instance->removeCookie('site');
        self::assertEquals(false, $this->instance->hasCookie('site'));
	}

    /**
     * @runInSeparateProcess
     */
	public function testHeader()
	{
        $this->instance->addHeader('id', 10);
        $this->instance->addHeader('name', 'Erandir Junior');
        $this->instance->addHeader('site', 'github');
        self::assertEquals(10, $this->instance->headers('id'));
        self::assertEquals(['name' => 'Erandir Junior', 'site' => 'github'], $this->instance->onlyHeaders(['name', 'site']));
        self::assertArrayHasKey('id', $this->instance->exceptHeaders(['name']));
        self::assertEquals($_SERVER, $this->instance->headers());

        $this->instance->removeHeader('site');
        self::assertEquals(false, $this->instance->hasHeader('site'));
	}

    /**
     * @runInSeparateProcess
     */
	public function testSession()
	{
        $this->instance->addSession('id', 10);
        $this->instance->addSession('name', 'Erandir Junior');
        $this->instance->addSession('site', 'github');
        self::assertEquals(10, $this->instance->session('id'));
        self::assertEquals(['name' => 'Erandir Junior', 'site' => 'github'], $this->instance->onlySession(['name', 'site']));
        self::assertArrayHasKey('id', $this->instance->exceptSession(['name']));
        self::assertEquals(['id' => 10, 'name' => 'Erandir Junior', 'site' => 'github'], $this->instance->session());

        $this->instance->removeSession('site');
        self::assertEquals(false, $this->instance->hasSession('site'));
	}

    /**
     * @runInSeparateProcess
     */
	public function testBody()
	{
        $this->instance->add('id', 10);
        $this->instance->add('name', 'Erandir Junior');
        $this->instance->add('site', 'github');
        self::assertEquals(10, $this->instance->get('id'));
        self::assertEquals(10, $this->instance->id);
        self::assertEquals(['name' => 'Erandir Junior', 'site' => 'github'], $this->instance->only(['name', 'site']));
        self::assertArrayHasKey('id', $this->instance->except(['name']));
        self::assertEquals(['id' => 10, 'name' => 'Erandir Junior', 'site' => 'github'], $this->instance->all());

        $this->instance->remove('site');
        self::assertEquals(false, $this->instance->has('site'));

        $body = $this->instance->bodyAsObject();
        self::assertEquals(10, $body->id);
        self::assertEquals('Erandir Junior', $body->name);
	}

    /**
     * @runInSeparateProcess
     */
	public function testMethod()
	{
        $_SERVER['REQUEST_METHOD'] = 'GET';
        self::assertEquals(true, $this->instance->isMethod('GET'));
        self::assertEquals(false, $this->instance->isMethod('POST'));
	}
}