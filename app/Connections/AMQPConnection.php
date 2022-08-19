<?php

namespace App\Connections;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPConnection extends AMQPStreamConnection
{
    public $exchange;
    public $queue;
    public $routingKey;
    public function __construct($pathToConf)
    {
        $conf = parse_ini_file($pathToConf);
        $host = $conf['host'];
        $port = $conf['port'];
        $user = $conf['user'];
        $password = $conf['password'];

        parent::__construct($host, $port, $user, $password);
    }

    public function publish_data($exchange, $queue, $routingKey)
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

    public function createJsonMessage($data)
    {
        return new AMQPMessage($data, [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);
    }

    public function closeConnection()
    {
        $this->channel()->close();
        $this->close();
    }
}
