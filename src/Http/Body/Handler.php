<?php

namespace PlugRoute\Http\Body;

use PlugRoute\Http\Globals\Server;

interface Handler
{
	public function handle(Server $server);
}