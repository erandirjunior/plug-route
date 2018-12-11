<?php
/**
 * Created by PhpStorm.
 * User: erandir
 * Date: 11/12/18
 * Time: 19:48
 */

namespace PlugRoute\Helpers;

class Novo
{
	public function handle($request, \Closure $next)  : callable
	{
		if (true) {
			$request->redirect('/plug-route/example');
		}
		return $next($request);
	}
}