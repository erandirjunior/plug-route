<?php
/**
 * Created by PhpStorm.
 * User: erandir
 * Date: 24/07/18
 * Time: 20:14
 */

namespace PlugRoute\Helpers;


class PlugHelper
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

    /**
     * Remove caracteres of match.
     *
     * @param $matches
     * @return mixed
     */
    public static function clearMatch($matches)
    {
        foreach ($matches[0] as $k => $v) {
            $matches[$k] = str_replace(['/{', '{', '}', '}/', '/'], '', $v);
        }

        return $matches;
    }

    /**
     * Return array of indexes where url parts are dynamics.
     *
     * @param array $routes
     * @param array $matches
     * @return mixed
     */
    public static function getIndexDynamicOnRoute(array $routes, array $matches)
    {
        array_walk($routes, function ($k, $v) use ($matches, &$indice) {
            foreach ($matches as $j => $value) {
                $value = str_replace(['{', '}', '/'], '', $value);
                if ($k == $value) {
                    $indice[$v] = $v;
                }
            }
        });
        return $indice;
    }

    /**
     * Return matches.
     *
     * @param $route
     * @return mixed
     */
    public static function getMatch($route)
    {
        preg_match_all('({.+?}/?)', $route, $match);
        return $match;
    }

    public static function getValuesDynamics(array $indexes, array $url)
    {
    	$data = [];
        foreach ($indexes as $k => $v) {
        	if (isset($url[$k])) {
				$data[] = $url[$k];
			}
        }
        return $data;
    }

    /**
     * Verify if two values are equals.
     *
     * @param $value1
     * @param $value2
     * @return bool
     */
    public static function isEqual($value1, $value2) {
        return $value1 === $value2;
    }

    /**
     * Verify if class exists.
     *
     * @param $class
     * @return bool
     */
    public static function classExist($class)
    {
        if (class_exists($class)) {
            return true;
        }
        return false;
    }

    /**
     * Verify if method exists.
     *
     * @param $class
     * @param $method
     * @return bool
     */
    public static function methodExist($class, $method)
    {
        if (method_exists($class, $method)) {
            return true;
        }

        return false;
    }

    /**
     * Remove empty values of a array.
     *
     * @param array $array
     * @return array
     */
    public static function removeEmptyValue(array $array)
    {
        return array_filter($array, function($v) {
            return !empty($v);
        });
    }

    /**
     * Return a array without values empty.
     *
     * @param $str
     * @param $separator
     */
    public static function toArray($str, $separator)
    {
        $array = explode($separator, $str);
        return self::removeEmptyValue($array);
    }
}