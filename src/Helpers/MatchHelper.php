<?php

namespace PlugRoute\Helpers;

class MatchHelper
{
    public static function getMatchCase(string $string, string $pattern = '{(.*?)}')
    {
        preg_match("/{$pattern}/", $string, $matches);

        return $matches;
    }
}
