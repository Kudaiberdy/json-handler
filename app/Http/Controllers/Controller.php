<?php

namespace App\Http\Controllers;

use App\Connections\AMQPConnection;
use App\Connections\DBClientConnection;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    private $dbClientConnection;
    private $amqpConnection;

    public function __construct()
    {
        $this->dbClientConnection = new DBClientConnection(
            __DIR__ . '/../../../configs/dbclientconnection.ini'
        );
        $this->amqpConnection = new AMQPConnection(__DIR__ . '/../../../configs/amqpconnection.ini');
    }

    public function DBClientConnection()
    {
        return $this->dbClientConnection;
    }

    public function AMQPConnection()
    {
        return $this->amqpConnection;
    }
}
