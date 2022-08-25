<?php

namespace App\Connections;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPConnection extends AMQPStreamConnection
{
    private string $exchange;
    private string $queue;
    private string $routingKey;

    public function __construct(string $pathToConf)
    {
        $conf = parse_ini_file($pathToConf);
        $host = $conf['host'];
        $port = $conf['port'];
        $user = $conf['user'];
        $password = $conf['password'];

        parent::__construct($host, $port, $user, $password);
    }

    /**
     * @param string $exchange
     * @param string $queue
     * @param string $routingKey
     * @return void
     */
    public function declareConnection(string $exchange, string $queue, string $routingKey): void
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

    /**
     * @param $data
     * @param $contentType
     * @param $deliveryMode
     * @return AMQPMessage
     */
    public function createJsonMessage(
        $data,
        $contentType = 'application/json',
        $deliveryMode = AMQPMessage::DELIVERY_MODE_PERSISTENT
    ): AMQPMessage {
        return new AMQPMessage($data, [
            'content_type' => $contentType,
            'delivery_mode' => $deliveryMode
        ]);
    }

    /**
     * @param $message
     * @return void
     */
    public function publishMessage($message): void
    {
        $this->channel()->basic_publish(
            $message,
            $this->exchange,
            $this->routingKey
        );
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function closeConnection(): void
    {
        $this->channel()->close();
        $this->close();
    }
}
