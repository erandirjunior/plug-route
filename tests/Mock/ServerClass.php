<?php

namespace PlugRoute\Test\Mock;

use PlugRoute\Http\Globals\Server;

class ServerClass extends Server
{
	public function getContentType()
	{
		$array = [
			'Content-Type: json'
		];
		return $this->clearHeadersFromHeadersList($array, 'Content-Type');
	}
}