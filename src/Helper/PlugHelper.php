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

    public static function clearRoute($route)
    {
        return preg_replace('/\/{2,}/', '/', $route);
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
            return $arr['type'] === $type || $arr['type'] === 'ANY';
        });
    }

    public static function clearMatch($matches)
    {
        foreach ($matches[0] as $k => $v) {
            $matches[$k] = str_replace(['/{', '{', '}', '}/', '/'], '', $v);
        }

        return $matches;
    }

    /**
     * Return dynamic indexes
     *
     * @param array $matches
     * @param array $routes
     * @return mixed
     */
    public static function getIndex(array $routes, array $matches)
    {
        array_walk($routes, function ($k, $v) use ($matches, &$indice) {
            foreach ($matches as $j => $value) {
                $value = str_replace(['{', '}', '/'], '', $value);
                if ($k == str_replace(['{', '}'], '', $value)) {
                    $indice[$v] = $v;
                }
            }
        });
        return $indice;


    }

    public static function isDynamic($route)
    {
        if (preg_match_all('({.+?}/?)', $route)) {
            return true;
        }
        return false;
    }

    public static function getMatch($route)
    {
        preg_match_all('({.+?}/?)', $route, $match);
        return $match;
    }
}