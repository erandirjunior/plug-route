<?php

namespace PlugRoute\Http\Globals;

use PlugRoute\Http\Body\Handler;

class Post implements Handler
{
	private $post;

	public function __construct()
	{
		$this->post = $_POST;
	}

	public function getBody()
	{
		return $this->post;
	}

	public function handle($server)
	{
		return $this->getBody();
	}
}