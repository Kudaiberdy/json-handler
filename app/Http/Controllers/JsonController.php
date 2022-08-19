<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilities\Json;
use App\Connections\AMQPConnection;

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
            $connection = new AMQPConnection(__DIR__ . '/../../../configs/amqp.ini');
            $connection->declare_connectioin('router', 'push-queue', 'push');
            $message = $connection->createJsonMessage($json);
            $connection->publish_message($message);
            $connection->closeConnection();

            return response('valid');
        }

        return response('invalid');
    }
}
