<?php

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

class ChatHandler implements MessageComponentInterface
{
    protected SplObjectStorage $clients;

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Handle new connection
        // Armazenar a nova conexão para enviar mensagens
        $this->clients->attach($conn);

        echo "Nova conexão! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Quando uma mensagem é recebida de um cliente, ela deve ser
        // retransmitida para todos os outros. No nosso caso, o cliente
        // não enviará mensagens diretamente para o WebSocket, a API fará isso
        // através do RabbitMQ.
        echo "Mensagem recebida do cliente {$from->resourceId}: {$msg}\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Handle connection close
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
        var_dump($e->getMessage());
    }

    public function broadcast($msg)
    {
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }
}
