<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilities\Json;

class JsonController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function find(Request $request): \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
    {
        $needle = $request->query('query');
        $haystack = $request->getContent();
        $result = Json::search($needle, $haystack);

        return response($result)->withHeaders(['Content-Type' => 'application/json']);
    }
}
