<?php

namespace PlugRoute\Rules\Http;

use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Helpers\RouteHelper;

class RequestService
{
	public function getDataRequest()
	{
        if ($this->contentTypeIsJson()) {
            return $this->getBodyJson();
        }

        if ($this->contentTypeIsFormData()) {
            return $this->getBodyFormData();
        }

        if ($this->contentTypeIsFormUrlencoded()) {
            return $this->getBodyFormUrlEncoded();
        }
	}

    public function getBodyPostRequest()
    {
        return $this->contentTypeIsJson() ? $this->getBodyJson() : $_POST;
	}

    private function getValuePhpInput()
    {
        return file_get_contents("php://input");
    }

    public function contentTypeIsJson()
    {
        return strpos(RequestHelper::getContentType(), 'json') !== false ? true : false;
	}

    public function contentTypeIsFormData()
    {
        return strpos(RequestHelper::getContentType(), 'form-data') !== false ? true : false;
	}

    public function contentTypeIsFormUrlencoded()
    {
        return strpos(RequestHelper::getContentType(), 'x-www-form-urlencoded') !== false ? true : false;
	}

    public function getBodyJson()
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