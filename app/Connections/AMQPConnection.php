<?php

namespace App\Connections;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPConnection extends AMQPStreamConnection
{
    private $exchange;
    private $queue;
    private $routingKey;

    public function __construct($pathToConf)
    {
        $conf = parse_ini_file($pathToConf);
        $host = $conf['host'];
        $port = $conf['port'];
        $user = $conf['user'];
        $password = $conf['password'];

        parent::__construct($host, $port, $user, $password);
    }

    public function declareConnectioin($exchange, $queue, $routingKey)
    {
        $this->exchange = $exchange;
        $this->queue = $queue;
        $this->routingKey = $routingKey;

        $chanel = $this->channel();
        $chanel->exchange_declare($exchange, 'direct');
        $chanel->queue_declare(
            $queue,
            false,
            true,
            false
        );
        $chanel->queue_bind($queue, $exchange, $routingKey);
    }

    public function createJsonMessage(
        $data,
        $contentType = 'application/json',
        $deliveryMode = AMQPMessage::DELIVERY_MODE_PERSISTENT
    ) {
        return new AMQPMessage($data, [
            'content_type' => $contentType,
            'delivery_mode' => $deliveryMode
        ]);
    }

    public function publishMessage($message)
    {
        $this->channel()->basic_publish(
            $message,
            $this->exchange,
            $this->routingKey
        );
    }

    public function closeConnection()
    {
        $this->channel()->close();
        $this->close();
    }
}
