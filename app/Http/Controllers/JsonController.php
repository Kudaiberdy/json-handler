<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilities\Json;

class JsonController extends Controller
{
    public function find(Request $request)
    {
        $needle = $request->query('query');
        $haystack = $request->getContent();
        $result = Json::search($needle, $haystack);
        $contentType = 'application/json';

        return response($result)->withHeaders(['Content-Type' => $contentType]);
    }
}
