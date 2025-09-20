<?php

namespace App\Application\Chat;

use App\Infrastructure\Adapters\MessageRepository;

interface SendMessageUseCaseInterface
{
    public function __construct(MessageRepository $messageRepository);
    public function execute(string $user, string $text): void;
}
