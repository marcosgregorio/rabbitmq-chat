<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Dotenv\Dotenv;
// use App\WebSocket\ChatHandler;

$dotEnv = Dotenv::createImmutable(__DIR__ . '/../');
$dotEnv->load();

$connection = new AMQPStreamConnection(
    $_ENV['RABBITMQ_HOST'],
    $_ENV['RABBITMQ_PORT'],
    $_ENV['RABBITMQ_USER'],
    $_ENV['RABBITMQ_PASS']
);

$channel = $connection->channel();

$channel->queue_declare('chat_messages', false, false, false, false);

echo "Aguardando mensagens..." . PHP_EOL;

$wsClient = stream_socket_client('tcp://localhost:8080', $errno, $errstr, 30);

if (!$wsClient) {
    echo "Erro ao conectar ao servidor WebSocket: $errstr ($errno)" . PHP_EOL;
    exit(1);
}

$callback = function (AMQPMessage $msg) use ($wsClient) {
    echo "Mensagem recebida: " . $msg->body . PHP_EOL;

    fwrite($wsClient, $msg->body);
};

$channel->basic_consume('chat_messages', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
