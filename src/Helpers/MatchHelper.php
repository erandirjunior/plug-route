<?php

namespace PlugRoute\Helpers;

class MatchHelper
{
    public static function getMatchAll($str, $pattern, $position = null)
    {
        preg_match_all("/{$pattern}/", $str, $matches);

        return !is_null($position) ? $matches[1] : $matches;
    }

    public static function getMatchCase(string $string, string $pattern = '{(.*?)}')
    {
        preg_match("/{$pattern}/", $string, $matches);

        return $matches;
    }
}
