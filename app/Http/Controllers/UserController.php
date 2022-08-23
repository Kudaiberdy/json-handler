<?php

namespace App\Http\Controllers;

use App\Connections\AMQPConnection;
use App\Connections\DBConnection;
use App\Utilities\Json;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create(
        Request $request,
        array $rules = ['name', 'phone', 'country', 'region', 'numberrange', 'email'],
        array $messages = [],
        array $customAttributes = []
    )
    {
        $json = $request->getContent();
        $res = Json::validate($json, $rules);

        if ($res) {
            $connection = new AMQPConnection(__DIR__ . '/../../../configs/amqpconnection.ini');
            $connection->declare_connectioin('router', 'push-queue', 'push');
            $message = $connection->createJsonMessage($json);
            $connection->publish_message($message);
            $connection->closeConnection();

            return response('valid');
        }

        return response('invalid');
    }

    public function index(Request $request)
    {
        [$key, $value] = explode('=', $request->getQueryString());
        $connection = new DBConnection(__DIR__ . '/../../../configs/dbconnection.ini');
        $result = $connection->index($key, $value);
        $contentType = 'application/json';

        return response($result)->withHeaders(['Content-Type' => $contentType]);
    }
}
