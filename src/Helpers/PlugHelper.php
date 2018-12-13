<?php

namespace PlugRoute\Helpers;

class PlugHelper
{
    /**
     * Remove caracteres of match.
     *
     * @param $matches
     * @return mixed
     */
    public static function clearArrayValues($matches)
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
        array_walk($routes, function ($k, $v) use ($matches, &$indexes) {
            foreach ($matches as $j => $value) {
                $value = str_replace(['{', '}', '/'], '', $value);
                $k = str_replace(['{', '}', '/'], '', $k);
                if ($k == $value) {
                    $indexes[$value] = $v;
                }
            }
        });
        return $indexes;
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

    public static function getMatch2($route)
    {
        preg_match_all('/({.+?})\/?/', $route, $match);
        return $match;
    }

    public static function getValuesDynamics(array $indexes, array $url)
    {
        $data = [];
        foreach ($indexes as $k => $v) {
            if (isset($url[$v])) {
                $data[$k] = $url[$v];
            }
        }
        return $data;
    }

    public static function removeEmptyValue(array $array)
    {
        return array_filter($array, function ($v) {
            return !empty($v);
        });
    }

    public static function returnArrayWithoutEmptyValues($str, $separator)
    {
        $array = explode($separator, $str);
        return self::removeEmptyValue($array);
    }
}