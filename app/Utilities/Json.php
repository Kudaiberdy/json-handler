<?php

namespace App\Utilities;

use function App\Utilities\ArrayHelpers\findInArr;

class Json
{
    static public function search(string $needle, array $jsonToArr)
    {
        return json_encode([$needle => findInArr($needle, $jsonToArr)]);
    }
}


