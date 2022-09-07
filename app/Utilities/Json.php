<?php

namespace App\Utilities;

use function App\Utilities\ArrayHelpers\findInArr;

class Json
{
    /**
     * @param string $needle
     * @param string $json
     * @return false|string
     */
    public static function search(string $needle, string $json): false|string
    {
        $jsonToArr = json_decode($json, true);
        return json_encode([$needle => findInArr($needle, $jsonToArr)], JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param string $json
     * @param array $validFields
     * @return bool
     */
    public static function isValid(string $json, array $validFields): bool
    {
        $jsonToArr = json_decode($json, true);
        $keys = array_keys($jsonToArr);
        $diff = array_diff($keys, $validFields);
        return empty($diff);
    }
}
