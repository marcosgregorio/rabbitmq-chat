<?php

namespace App\Domain;

class Message
{
    private string $user;
    private string $text;

    public function __construct(string $user, string $text)
    {
        $this->user = $user;
        $this->text = $text;
    }

    public function toArray(): array
    {
        return [
            'user' => $this->user,
            'text' => $this->text,
            'timestamp' => time(),
        ];
    }
}
