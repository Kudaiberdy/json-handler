<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function App\FindInJson\findInJson;

class FindInJsonController extends Controller
{
    public function find(Request $request)
    {
        $needle = $request->query('query');
        $haystack = json_decode($request->getContent(), true);
        $result = findInJson($needle, $haystack);
        return response()->json($result, 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_SLASHES);
    }
}
