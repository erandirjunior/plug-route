<?php

namespace PlugRoute\Helpers;

class RequestHelper
{
    /**
     * Return url path.
     *
     * @return string
     */
    public static function getUrlPath()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Return request type.
     *
     * @return string
     */
    public static function getTypeRequest()
    {
        return parse_url($_SERVER['REQUEST_METHOD'], PHP_URL_PATH);
    }

    public static function getBodyDecode()
	{
		return json_decode(file_get_contents("php://input"), true);
	}

    public static function getBodyFormData()
	{
		echo "1 = ".var_dump(parse_str(file_get_contents("php://input"),$post_vars));
		preg_match_all('/"(\\w+)"\\s(.+?)\s/', $x, $matches);

		var_dump($matches);
	}

	public static function getContentType()
	{
		return $_SERVER['CONTENT_TYPE'];
	}

	public function getBodyFormUrlEncoded()
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