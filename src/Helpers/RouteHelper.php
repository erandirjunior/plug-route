<?php

namespace PlugRoute\Helpers;

class RouteHelper
{
    /**
     * Return new route path.
     *
     * @param $routeBase
     * @param $routeComplement
     * @return null|string|string[]
     */
    public static function pathRoute($routeBase, $routeComplement)
    {
        $pathRoute = "{$routeBase}/{$routeComplement}";
        $pathRoute = self::clearRoute($pathRoute);
        return $pathRoute;
    }

    /**
     * Removes repeated bars.
     *
     * @param $value
     * @return null|string|string[]
     */
    public static function clearRoute($value)
    {
        return preg_replace('/\/{2,}/', '/', $value);
    }

    /**
     * Verify if route is dynamic.
     *
     * @param $route
     * @return bool
     */
    public static function isDynamic($route)
    {
        if (preg_match_all('({.+?}/?)', $route)) {
            return true;
        }
        return false;
    }

	public static function removeCaractersOfString($str, array $caracters)
	{
		return str_replace($caracters, '', $str);
    }
}