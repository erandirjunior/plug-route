<?php
/**
 * Created by PhpStorm.
 * User: erandir
 * Date: 24/07/18
 * Time: 20:14
 */

namespace PlugRoute\Helper;


class PlugHelper
{
    public static function pathRoute($routeBase, $routeComplement)
    {
        $pathRoute = "{$routeBase}/{$routeComplement}";
        $pathRoute = preg_replace('/\/{2,}/', '/', $pathRoute);
        return $pathRoute;
    }

    public static function getUrlPath()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public static function getTypeRequest()
    {
        return parse_url($_SERVER['REQUEST_METHOD'], PHP_URL_PATH);
    }

    public static function filter($array)
    {
        $type = self::getTypeRequest();
        return array_filter($array, function($arr) use ($type) {
            return $arr['type'] === $type;
        });
    }
}