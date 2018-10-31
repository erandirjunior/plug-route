<?php

namespace PlugRoute\Services;

use PlugRoute\Helpers\RequestHelper;

class RequestService
{
	public function getDataRequest()
	{
        switch (RequestHelper::getContentType()) {
            case 'application/x-www-form-urlencoded':
                return $this->getBodyFormData();
                break;
            case 'application/json':
                return $this->getBodyDecode();
                break;
            default:
                return $this->getBodyFormUrlEncoded();
                break;
        }
	}

	private function getData()
	{
		switch (RequestHelper::getContentType()) {
			case 'application/x-www-form-urlencoded':
			    return RequestHelper::getBodyFormData();
				break;
			case 'application/json':
			    return RequestHelper::getBodyDecode();
				break;
			default:
			    RequestHelper::getBodyFormUrlEncoded();
				break;
		}
	}

    public static function getBodyDecode()
    {
        var_dump(json_decode(file_get_contents("php://input"), true));
    }

    public static function getBodyFormData()
    {
        //echo "1 = ".var_dump(parse_str(file_get_contents("php://input"),$post_vars));
        $x = parse_str(file_get_contents("php://input"),$post_vars);
        //preg_match_all('/"(\\w+)"\\s(.+?)\s/', $x, $matches);

        $content = file_get_contents("php://input");

//        $content = str_replace('&');

        var_dump($content);
    }

    public static function getBodyFormUrlEncoded()
    {
        $raw_data = file_get_contents('php://input');
        $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));
        echo $raw_data;
        var_dump($raw_data);
        var_dump($boundary);
        $v = '/"(.+)"+\n+(.+)/';
        // echo preg_match_all($v, $raw_data, $matches);
        echo preg_match_all('/"(.+)"+\s+(.*)/', $raw_data, $matches);
    }
}