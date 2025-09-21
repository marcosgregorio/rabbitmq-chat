<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Application\Chat\SendMessageUseCase;
use App\Infrastructure\Adapters\MessageRepository;
use App\Infrastructure\Adapters\RabbitMQAdapter;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

require __DIR__ . '/../vendor/autoload.php';

$dotEnv = Dotenv::createImmutable(__DIR__ . '/../');
$dotEnv->load();

$app = AppFactory::create();

$app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($app): ResponseInterface {
  if ($request->getMethod() === 'OPTIONS') {
    $response = $app->getResponseFactory()->createResponse();
    $response = $response->withStatus(200);
    $response = $response
      ->withHeader('Acess-Control-Allow-Credentials', 'true')
      ->withHeader('Access-Control-Allow-Origin', '*')
      ->withHeader('Access-Control-Allow-Headers', '*')
      ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
      ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
      ->withHeader('Pragma', 'no-cache');
    return $response;
  } else {
    $response = $handler->handle($request);
  }

  $response = $response
    ->withHeader('Acess-Control-Allow-Credentials', 'true')
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', '*')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
    ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
    ->withHeader('Pragma', 'no-cache');

  if (ob_get_contents()) {
    ob_clean();
  }

  return $response;
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
  $data = json_decode($request->getBody()->getContents(), true);
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

$app->run();
