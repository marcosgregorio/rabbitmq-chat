<?php

require __DIR__ . '/../vendor/autoload.php';

use WebSocket\Client;

$client = new Client("ws://localhost:8080");
$client->send("Hello WebSocket!");
$client->receive()
echo $client->receive();