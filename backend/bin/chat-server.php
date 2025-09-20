<?php

require __DIR__ . '/../vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSocket\ChatHandler;

$app = new ChatHandler();

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            $app
        ),

    ),
    8080 // Porta do servidor WebSocket
);

echo "Servidor WebSocket iniciado em ws://localhost:8080\n";
$server->run();
