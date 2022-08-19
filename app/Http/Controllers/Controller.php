<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Utilities\DBConnection;

class Controller extends BaseController
{
    public function index(Request $request)
    {
        [$key, $value] = explode('=', $request->getQueryString());
        $connection = new DBConnection();
        $result = $connection->index($key, $value);
        $contentType = 'application/json';

        return response($result)->withHeaders(['Content-Type' => $contentType]);
    }
}
