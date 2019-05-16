<?php

namespace PlugRoute\Test\Classes;

use PlugHttp\Body\Content;
use PlugHttp\File;
use PlugHttp\Get;
use PlugHttp\Globals\GlobalFile;
use PlugHttp\Globals\GlobalGet;
use PlugHttp\Globals\GlobalServer;
use PlugHttp\Server;
use PlugRoute\Http\Request;

class RequestCreator extends \PlugRoute\Http\RequestCreator
{
	public static function create()
	{
		$_SERVER['REQUEST_URI'] = '/';
		$_SERVER['REQUEST_METHOD'] = 'GET';

		$get 		= Get::create();
		$file 		= File::create();
		$server 	= new GlobalServer($_SERVER);
		$content 	= (new Content($server))->getBody();
		return self::createRequest($get, $content, $server, $file);
	}

	public static function createDynamic()
	{
		$_SERVER['REQUEST_URI'] = '/test';
		$_SERVER['REQUEST_METHOD'] = 'GET';

		$get 		= Get::create();
		$file 		= File::create();
		$server 	= new GlobalServer($_SERVER);
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