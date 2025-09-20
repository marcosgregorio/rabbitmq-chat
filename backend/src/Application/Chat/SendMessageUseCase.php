<?php

namespace App\Application;

use App\Application\Chat\SendMessageUseCaseInterface;
use App\Domain\Message;
use App\Infrastructure\Adapters\MessageRepository;

class SendMessageUseCase implements SendMessageUseCaseInterface
{
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function execute(string $user, string $text): void
    {
        $message = new Message($user, $text);
        $this->messageRepository->send($message);
    }
}
