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

	private function getValuePhpInput()
    {
        return file_get_contents("php://input");
    }

    public function getBodyDecode()
    {
        return json_decode($this->getValuePhpInput(), true);
    }

    public function getBodyFormData()
    {
		preg_match_all('/"(.+)"+\s+(.*)/', $this->getValuePhpInput(), $matches);

		foreach ($matches[1] as $key => $match) {
			$matchKey = RouteHelper::removeCaractersOfString($match, ['\'', "\""]);
			$matchValue = RouteHelper::removeCaractersOfString($matches[2][$key], ['\'', "\""]);
			$array[$matchKey] = $matchValue;
		}

		return $array;
	}

    public function getBodyFormUrlEncoded()
    {
		$content = $this->getValuePhpInput();

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