<?php

namespace PlugRoute\Helpers;

class ValidateHelper
{
    /**
     * Verify if two values are equals.
     *
     * @param $route
     * @param $url
     * @return bool
     */
    public static function isEqual($route, $url)
    {
        $chekLastCaracter = substr("testers", -1); 

        if($chekLastCaracter == '/'){
            return substr($route, 0, -1) === $url;
        }

        return $route === $url;
    }

    /**
     * Verify if class exist.
     *
     * @param $class
     * @return bool
     */
    public static function classExist($class)
    {
        return class_exists($class) ? true : false;
    }

    /**
     * Verify if method exist.
     *
     * @param $class
     * @param $method
     * @return bool
     */
    public static function methodExist($class, $method)
    {
        return method_exists($class, $method) ? true : false;
    }
}