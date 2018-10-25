<?php

namespace PlugRoute\Helpers;

class ValidateHelper
{
    /**
     * Verify if two values are equals.
     *
     * @param $value1
     * @param $value2
     * @return bool
     */
    public static function isEqual($value1, $value2)
    {
        return $value1 === $value2;
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