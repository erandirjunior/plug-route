<?php

namespace PlugRoute\Http\Body;

use PlugRoute\Http\Globals\Server;

class Json implements Handler, Advancer
{
	private Handler $handler;

    private const CONTENT_TYPE = 'json';

	public function getBody($content)
	{
		return json_decode($content, true);
	}

	public function next(Handler $handler)
	{
		$this->handler = $handler;
	}

	public function handle(Server $server)
	{
		if ($server->contentTypeIs(self::CONTENT_TYPE)) {
			return $this->getBody($server->getContent());
		}
		
		return $this->handler->handle($server);
	}
}