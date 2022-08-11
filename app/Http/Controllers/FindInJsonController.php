<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function App\Utilities\Json;

class FindInJsonController extends Controller
{
    public function search(Request $request)
    {
        $needle = $request->query('query');
        $haystack = $request->getContent();
        $result = Json::search($needle, $haystack);
        return response()->json($result, 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_SLASHES);
    }
}
