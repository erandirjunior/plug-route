<?php

namespace PlugRoute\Test\Mock;

use PlugRoute\Http\Globals\Server;

class ServerClassFormData extends Server
{
	public function getContentType()
	{
		$array = [
			'Content-Type: multipart/form-data'
		];
		return $this->clearHeadersFromHeadersList($array, 'Content-Type');
	}

	public function getContent()
	{
		return '
            ----------------------------709120409036452154444464
Content-Disposition: form-data; name="value"

100
----------------------------709120409036452154444464--';
	}
}