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
}