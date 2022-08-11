<?php

namespace PlugRoute\Test\Mock;

use PlugRoute\Http\Globals\Server;

class ServerClassUrlEncoded extends Server
{
	private $flag;

	public function getContentType()
	{
		$array = [
			'Content-Type: x-www-form-urlencoded'
		];
		return $this->clearHeadersFromHeadersList($array, 'Content-Type');
	}

	public function getContent()
	{
		if ($this->flag === 1) {
			return 'test=myTest';
		}

		return 'test=myTest&lang=PHP&dev=Erandir';
	}

	public function flag($flag)
	{
		$this->flag = $flag;
	}
}