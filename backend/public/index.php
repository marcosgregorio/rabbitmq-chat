<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Application\SendMessageUseCase;
use App\Infrastructure\Adapters\MessageRepository;
use App\Infrastructure\Adapters\RabbitMQAdapter;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require __DIR__ . '/../vendor/autoload.php';

$dotEnv = DotEnv\DotEnv::createImmutable(__DIR__ . '/../');
$dotEnv->load();

$app = AppFactory::create();

$app->add(function (Request $request, RequestHandler $handler) use ($app) {
  $response = $handler->handle($request);
  return $response
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->options('/{routes:.+}', function (Request $request, Response $response) {
  return $response;
});

$rabbitMQAdapter = new RabbitMQAdapter(
  $_ENV['RABBITMQ_HOST'],
  $_ENV['RABBITMQ_PORT'],
  $_ENV['RABBITMQ_USER'],
  $_ENV['RABBITMQ_PASS']
);

$messageRepository = new MessageRepository($rabbitMQAdapter);

$app->post('/messages', function (Request $request, Response $response) use ($messageRepository) {
  $data = $request->getParsedBody();
  $user = $data['user'] ?? null;
  $message = $data['text'] ?? null;

  if (!$user || !$message) {
    $response->getBody()->write(json_encode(['error' => 'User and message are required']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
  }

  $useCase = new SendMessageUseCase($messageRepository);
  $useCase->execute($user, $message);

  $response->getBody()->write(json_encode(['status' => 'Message sent']));
  return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});
