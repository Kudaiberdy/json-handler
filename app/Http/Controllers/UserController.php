<?php

namespace App\Http\Controllers;

use App\Connections\AMQPConnection;
use App\Connections\DBClientConnection;
use App\Connections\DBConnection;
use App\Utilities\Json;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create(
        Request $request,
        array $rules = ['name', 'phone', 'country', 'region', 'numberrange', 'email'],
    ) {
        $json = $request->getContent();

        if (Json::isValid($json, $rules)) {
            $connection = new AMQPConnection(__DIR__ . '/../../../configs/amqpconnection.ini');
            $connection->declareConnectioin('router', 'push-queue', 'push');
            $message = $connection->createJsonMessage($json);
            $connection->publishMessage($message);
            $connection->closeConnection();

            return response('valid');
        }

        return response('invalid');
    }

    public function index(Request $request)
    {
        [$key, $value] = explode('=', $request->getQueryString());
        $connection = new DBClientConnection(__DIR__ . '/../../../configs/dbclientconnection.ini');
        $result = $connection->index($key, $value);
        $contentType = 'application/json';

        return response($result)->withHeaders(['Content-Type' => $contentType]);
    }
}
