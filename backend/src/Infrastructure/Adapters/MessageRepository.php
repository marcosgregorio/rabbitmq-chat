<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Message;

class MessageRepository
{
    private RabbitMQAdapter $adapter;

    public function __construct(RabbitMQAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function send(Message $message): void
    {
        $this->adapter->publish($message->toArray());
    }
}
