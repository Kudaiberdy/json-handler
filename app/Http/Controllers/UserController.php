<?php

namespace App\Http\Controllers;

use App\Connections\AMQPConnection;
use App\Connections\DBClientConnection;
use App\Utilities\Json;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @param array $rules
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function create(
        Request $request,
        array $rules = ['name', 'phone', 'country', 'region', 'numberrange', 'email'],
    ): \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory {
        $json = $request->getContent();

        if (Json::isValid($json, $rules)) {
            $amqpConnection = new AMQPConnection(__DIR__ . '/../../../configs/amqpconnection.ini');
            $amqpConnection->declareConnection('router', 'push-queue', 'push');
            $message = $amqpConnection->createJsonMessage($json);
            $amqpConnection->publishMessage($message);
            $amqpConnection->closeConnection();

            return response('Data is valid');
        }

        return response('Data invalid');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function index(Request $request): \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
    {
        [$key, $value] = explode('=', $request->getQueryString());
        $connection = new DBClientConnection(
            __DIR__ . '/../../../configs/dbclientconnection.ini'
        );
        $result = $connection->index($key, $value);

        return response($result)->withHeaders(['Content-Type' => 'application/json']);
    }
}
