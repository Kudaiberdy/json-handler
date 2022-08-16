<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilities\Json;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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
            $connection = new AMQPStreamConnection(
                'localhost',
                5672,
                'guest',
                'guest'
            );
            $channel = $connection->channel();
            $channel->exchange_declare(
                'router',
                'direct'
            );

            $channel->queue_declare(
                'push-queue',
                false,
                true,
                false
            );

            $channel->queue_bind(
                'push-queue',
                'router',
                'push'
            );

            $message = new AMQPMessage($json, [
                'content_type' => 'application/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]);

            $channel->basic_publish(
                $message,
                'router',
                'push'
            );

            $channel->close();
            $connection->close();
            return response('valid');
        }
        return response('invalid');
    }
}
