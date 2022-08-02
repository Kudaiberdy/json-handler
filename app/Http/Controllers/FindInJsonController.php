<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function App\FindInJson\find;

class FindInJsonController extends Controller
{
    public function search(Request $request)
    {
        $needle = $request->query('query');
        $haystack = json_decode($request->getContent(), true);
        $result = find($needle, $haystack);
        return response()->json($result, 200, ['Content-Type' => 'application/json'], JSON_UNESCAPED_SLASHES);
    }
}
