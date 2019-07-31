<?php

namespace PlugRoute\Http;

use PlugHttp\Body\Content;
use PlugHttp\File;
use PlugHttp\Get;
use PlugHttp\Globals\GlobalFile;
use PlugHttp\Globals\GlobalGet;
use PlugHttp\Globals\GlobalServer;
use PlugHttp\Server;

class RequestCreator extends \PlugHttp\Request
{
	public static function create()
	{
		$get 		= Get::create();
		$file 		= File::create();
		$server 	= Server::create();
		$content 	= (new Content($server))->getBody();
		return self::createRequest($get, $content, $server, $file);
	}

	public static function createRequest(
		GlobalGet $get,
		$content,
		GlobalServer $server,
		GlobalFile $file
	)
	{
		return new Request($content, $get, $file, $server);
	}
}