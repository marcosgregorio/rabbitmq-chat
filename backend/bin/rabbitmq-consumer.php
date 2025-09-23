<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Dotenv\Dotenv;

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
$host = "localhost";
$port = 8080;

$socket = stream_socket_client("tcp://$host:$port", $errno, $errstr, 30);

$key = base64_encode(random_bytes(16));

$headers = "GET / HTTP/1.1\r\n"
         . "Host: $host:$port\r\n"
         . "Upgrade: websocket\r\n"
         . "Connection: Upgrade\r\n"
         . "Sec-WebSocket-Key: $key\r\n"
         . "Sec-WebSocket-Version: 13\r\n\r\n";

fwrite($socket, $headers);

// Ler resposta do servidor (handshake)
$response = fread($socket, 1024);
echo $response;
$callback = function (AMQPMessage $msg) use ($wsClient) {
    echo "Mensagem recebida: " . $msg->getBody() . PHP_EOL;

};

$channel->basic_consume('chat_messages', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
