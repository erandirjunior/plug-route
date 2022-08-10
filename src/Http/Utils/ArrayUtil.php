<?php

namespace PlugRoute\Http\Utils;

use stdClass;

class ArrayUtil
{
	public static function only(array $data, array $accept): array
	{
		return self::check($data, $accept, true);
	}

	public static function except(array $data, array $except): array
	{
		return self::check($data, $except, false);
	}

	private static function check($data, $keys, $in): array
	{
		$array = [];

		foreach ($data as $k => $v) {
			if (in_array($k, $keys) === $in) {
				$array[$k] = $v;
			}
		}

		return $array;
	}

    public static function convertToObject(array $data): stdClass
    {
        $object = new stdClass();

        foreach ($data as $key => $value) {
            $object->$key = $value;
        }

        return $object;
	}
}