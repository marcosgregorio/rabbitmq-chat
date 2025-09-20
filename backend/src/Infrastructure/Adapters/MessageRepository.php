<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Message;

class MessageRepository
{
    private RabbitMQAdapter $rabbitMQAdapter;

    public function __construct(RabbitMQAdapter $rabbitMQAdapter)
    {
        $this->rabbitMQAdapter = $rabbitMQAdapter;
    }

    public function send(Message $message): void
    {
        $this->rabbitMQAdapter->publish($message->toArray());
    }
}
