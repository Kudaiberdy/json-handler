<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilities\Json;

class JsonController extends Controller
{
    public function search(Request $request)
    {
        $needle = $request->query('query');
        $haystack = $request->getContent();
        $result = Json::search($needle, $haystack);
        return response()->json($result, 200, ['Content-Type' => 'application/json']);
    }

    public function validate(
        Request $request,
        array $rules = ['name', 'phone', 'country', 'region', 'numberrange', 'email'],
        array $messages = [],
        array $customAttributes = []
    )
    {
        $json = $request->getContent();
        $res = Json::validate($json, $rules);
        if ($res) {
            return response('valid');
        }
        return response('invalid');
    }
}
