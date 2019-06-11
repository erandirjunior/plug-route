<?php

namespace PlugRoute\Example;

use PlugRoute\Http\Request;

class Home
{
	public function example()
	{
		echo 'Teste route json!';
	}

	public function anything(Request $request)
	{
		echo "You access {$request->parameter('anything')} page!";
	}

	public function rankingChampions()
	{
		echo 'Ranking Champions!';
	}

	public function rankingF1()
	{
		echo 'Ranking F1!';
	}

	public function rankingXadrez()
	{
		echo 'Ranking Xadrez!';
	}
}