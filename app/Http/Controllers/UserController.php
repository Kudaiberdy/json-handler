<?php

namespace App\Http\Controllers;

use App\Connections\AMQPConnection;
use App\Connections\DBClientConnection;
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
            $cache = $this->cache();
            $emailsFromDb = $cache->get('emails');
            $email = json_decode($json, true)['email'];

            if (in_array($email, $emailsFromDb)) {
                return response('Email already exists');
            }

            $amqpConnection = $this->AMQPConnection();
            $amqpConnection->declareConnectioin('router', 'push-queue', 'push');
            $message = $amqpConnection->createJsonMessage($json);
            $amqpConnection->publishMessage($message);
            $amqpConnection->closeConnection();

            return response('User successfully created');
        }

        return response('Data invalid');
    }

    public function index(Request $request)
    {
        [$key, $value] = explode('=', $request->getQueryString());
        $connection = $this->DBClientConnection();
        $result = $connection->index($key, $value);
        $contentType = 'application/json';

        return response($result)->withHeaders(['Content-Type' => $contentType]);
    }
}
