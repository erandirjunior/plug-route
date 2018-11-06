<?php

namespace PlugRoute\Services;

use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Helpers\RouteHelper;

class RequestService
{
	public function getDataRequest()
	{
        switch ($this->returnCodeOfTypeRequest()) {
            case 1:
                return $this->getBodyFormUrlEncoded();
            case 2:
                return $this->getBodyDecode($this->getValuePhpInput());
            case 3:
                return $this->getBodyFormData();
        }
	}

	private function returnCodeOfTypeRequest()
    {
        $contentType = RequestHelper::getContentType();

        if (strpos($contentType, 'application/x-www-form-urlencoded') !== false) {
            return 1;
        }

        if (strpos($contentType, 'application/json') !== false) {
            return 2;
        }

        if (strpos($contentType, 'application/form-data') !== false) {
            return 3;
        }
    }

	public function manipulatePost($post)
    {
        switch (RequestHelper::getContentType())
        {
            case 'application/json' :
                return $this->getBodyDecode($post);
            default :
                return $post;
        }
    }

	private function getValuePhpInput()
    {
        return file_get_contents("php://input");
    }

    public function getBodyDecode($value)
    {
        return json_decode($value, true);
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