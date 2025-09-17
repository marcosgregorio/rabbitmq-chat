<?php

namespace App\Infrastructure\Adapters;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQAdapter
{
    private AMQPStreamConnection $connection;
    private $channel;

    public function __construct(string $host, int $port, string $user, string $password)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
        $this->channel = $this->connection->channel();

        $this->channel->queue_declare('chat_messages', false, false, false, false);
    }

    public function publish(array $data): void
    {
        $msg = new AMQPMessage(json_encode($data));
        $this->channel->basic_publish($msg, '', 'chat_messages');

        $this->channel->close();
        $this->connection->close();
    }
}
