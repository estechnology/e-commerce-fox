<?php
require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Controllers\HelloController;

$app = AppFactory::create();

// Definir a rota Hello World
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hello, World!");
    return $response;
});

// Rota para exibir Hello World com Twig
$app->get('/hello-twig', [HelloController::class, 'index']);

// Rodar a aplicação
$app->run();