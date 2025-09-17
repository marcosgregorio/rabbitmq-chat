<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Application\SendMessageUseCase;
use App\Infrastructure\Adapters\MessageRepository;
use App\Infrastructure\Adapters\RabbitMQAdapter;
use Slim\Handlers\Strategies\RequestHandler;

require __DIR__ . '/../vendor/autoload.php';

$dotEnv = DotEnv\DotEnv::createImmutable(__DIR__ . '/../');
$dotEnv->load();

$app = AppFactory::create();

$app->add(function (Request $request, RequestHandler $handler) use ($app) {
  $handler->handle
  $response = $handler->handle($request);
  return $response
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});
