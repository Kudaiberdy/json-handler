<?php

namespace App\Http\Controllers;

use App\Connections\AMQPConnection;
use App\Connections\DBClientConnection;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    private $dbClientConnection;
    private $cache;
    private $amqpConnection;

    public function __construct()
    {
        $this->dbClientConnection = new DBClientConnection(
            __DIR__ . '/../../../configs/dbclientconnection.ini'
        );

        $this->cache = new \Memcached();
        $params = parse_ini_file(__DIR__ . '/../../../configs/memcached.ini');
        $host = $params['host'];
        $port = $params['port'];
        $this->cache->addServer($host, $port);

        $this->amqpConnection = new AMQPConnection(__DIR__ . '/../../../configs/amqpconnection.ini');
    }

    public function DBClientConnection()
    {
        return $this->dbClientConnection;
    }

    public function cache()
    {
        return $this->cache;
    }

    public function AMQPConnection()
    {
        return $this->amqpConnection;
    }
}
