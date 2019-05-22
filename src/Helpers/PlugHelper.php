<?php

namespace PlugRoute\Helpers;

class PlugHelper
{
    public static function getMatchAll($route, $pattern = '{(.*?)}')
    {
        preg_match_all("/{$pattern}/", $route, $match);
        return $match[0];
    }

    public static function getMatchCase(string $route, string $pattern = '{(.*?)}')
    {
        $pattern = "/{$pattern}/";
        preg_match($pattern, $route, $matches);
        return $matches;
    }

    public static function stringToArray($str, $delimiter = '/')
    {
        return explode($delimiter, $str);
    }

    public static function replace($search, $replace, $subject)
    {
        return str_replace($search, $replace, $subject);
    }

    public static function removeValuesByIndex(array $array, array $indexes)
    {
        $arr = [];
        foreach ($array as $k => $v) {
            if (!in_array($k, $indexes)) {
                $arr[] = $v;
            }
        }
        return $arr;
    }
}
