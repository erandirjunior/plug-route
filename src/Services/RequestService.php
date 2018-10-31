<?php

namespace PlugRoute\Services;

use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Helpers\RouteHelper;

class RequestService
{
	public function getDataRequest()
	{
        switch (RequestHelper::getContentType()) {
            case 'application/x-www-form-urlencoded':
                return $this->getBodyFormUrlEncoded();
                break;
            case 'application/json':
                return $this->getBodyDecode();
                break;
            default:
                return $this->getBodyFormData();
                break;
        }
	}

    public static function getBodyDecode()
    {
        return json_decode(file_get_contents("php://input"), true);
    }

    public static function getBodyFormData()
    {
		$content = file_get_contents('php://input');
		preg_match_all('/"(.+)"+\s+(.*)/', $content, $matches);

		foreach ($matches[1] as $key => $match) {
			$matchKey = RouteHelper::removeCaractersOfString($match, ['\'', "\""]);
			$matchValue = RouteHelper::removeCaractersOfString($matches[2][$key], ['\'', "\""]);
			$array[$matchKey] = $matchValue;
		}

		return $array;
	}

    public static function getBodyFormUrlEncoded()
    {
		$content = file_get_contents("php://input");

		if (!strpos($content, '&')) {
			$array = explode('=', $content);
			return [$array[0] => $array[1]];
		}

		$array = explode('&', $content);

		foreach ($array as $value) {
			$aux = explode('=', $value);
			$arrayFormated[$aux[0]] = $aux[1];
		}
		return $arrayFormated;
    }
}