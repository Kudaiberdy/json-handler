<?php

namespace App\Connections;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPConnection extends AMQPStreamConnection
{
    public $connection;

    public function __construct($pathToConf)
    {
        $conf = parse_ini_file($pathToConf);
        $host = $conf['host'];
        $port = $conf['port'];
        $user = $conf['user'];
        $password = $conf['password'];

        parent::__construct(...$conf);
    }

    public function exchange_declare($params = [])
    {
        $this->channel->queue_declare(
            'push-queue',
            false,
            true,
            false
        );
    }

    public function queue_declare($params = [])
    {
        $this->channel->queue_declare(
            'push-queue',
            false,
            true,
            false
        );

        $this->channel->queue_bind(
            'push-queue',
            'router',
            'push'
        );
    }
}
