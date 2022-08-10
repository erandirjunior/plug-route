<?php

namespace PlugRoute\Test\Mock;

use PlugRoute\Http\Globals\Server;

class ServerClassTextPlain extends Server
{
	public function getContentType()
	{
		$array = [
			'Content-Type: text/plain'
		];

		return $this->clearHeadersFromHeadersList($array, 'Content-Type');
	}

	public function getContent()
	{
		return 'Text of example';
	}
}