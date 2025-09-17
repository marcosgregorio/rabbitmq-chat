<?php

namespace App\Application;

use App\Domain\Message;
use App\Infrastructure\Adapters\MessageRepository;

class SendMessageUseCase
{
    private MessageRepository $repository;

    public function __construct(MessageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $user, string $text): void
    {
        $message = new Message($user, $text);
        $this->repository->send($message);
    }
}
