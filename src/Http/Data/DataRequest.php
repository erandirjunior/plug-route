<?php

namespace PlugRoute\Http\Data;

use PlugRoute\Helpers\RequestHelper;
use PlugRoute\Helpers\RouteHelper;

trait DataRequest
{
	use DataServer;

	public function getRequisitionBody($method) {
		switch ($method) {
			case 'GET' :
				return $this->get();
				break;
			case 'POST' :
				return $this->getBodyPostRequest();
				break;
			default :
				return $this->getDataRequest();
		}
	}

	private function get()
    {
        return $_GET;
    }

	private function getDataRequest()
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
        return strpos($this->getContentType(), 'json') !== false ? true : false;
	}

    public function contentTypeIsFormData()
    {
        return strpos($this->getContentType(), 'form-data') !== false ? true : false;
	}

    public function contentTypeIsFormUrlencoded()
    {
        return strpos($this->getContentType(), 'x-www-form-urlencoded') !== false ? true : false;
	}

    public function getBodyJson()
    {
        return json_decode($this->getValuePhpInput(), true);
    }

    public function getBodyFormData()
    {
		preg_match_all('/"(.+)"+\s+(.+(?:-{5,})?)/', $this->getValuePhpInput(), $matches);

		$array = [];

		foreach ($matches[1] as $key => $match) {
			$matchKey = RouteHelper::removeCaractersOfString($match, ['\'', "\""]);

            $array[$matchKey] = $this->getValueFormData($matches[2][$key]);
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

    public function getValueFormData($value)
    {
        $valueCleared   = RouteHelper::removeCaractersOfString($value, ['\'', "\""]);
        $onlyHasTraces  = preg_split("/-{20,}/", $valueCleared, PREG_SPLIT_OFFSET_CAPTURE);
        return count($onlyHasTraces) > 1 ? '' : $valueCleared;
    }
}
