<?php

namespace App\Http\Controllers;

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
            $cache = $this->cache();
            $emailsFromCache = $cache->get('emails') ?: [];
            $currentEmail = json_decode($json, true)['email'];

            if (in_array($currentEmail, $emailsFromCache)) {
                return response('Email already exists');
            }

            $amqpConnection = $this->AMQPConnection();
            $amqpConnection->declareConnection('router', 'push-queue', 'push');
            $message = $amqpConnection->createJsonMessage($json);
            $amqpConnection->publishMessage($message);
            $amqpConnection->closeConnection();

            return response('User successfully created');
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
        $connection = $this->DBClientConnection();
        $cache = $this->cache();
        $cacheKey = "{$key}:{$value}";
        $result = $cache->get($cacheKey);

        if ($result === false) {
            $result = $connection->index($key, $value);
        }

        return response($result)->withHeaders(['Content-Type' => 'application/json']);
    }
}
