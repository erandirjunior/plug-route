<?php
/**
 * Created by PhpStorm.
 * User: erandir
 * Date: 11/12/18
 * Time: 19:48
 */

namespace PlugRoute\Helpers;

class Teste
{
	public function handle($request, \Closure $next) : callable
	{
//		var_dump($request);
//		return $request;
		return $next($request);
	}
}