<?php
require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Controllers\HelloController;
use App\Controllers\CartController;
use App\Database;

$config = require __DIR__ . '/../config/config.php';
$db = new Database($config['db']);

$app = AppFactory::create();

// Definir a rota Hello World
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hello, World!");
    return $response;
});

// Rota para exibir Hello World com Twig
$app->get('/hello-twig', function (Request $request, Response $response, array $args) use ($db) {
    $controller = new HelloController($db);
    return $controller->index($request, $response);
});

// Rota de teste para verificar a conexão com o banco de dados
$app->get('/test-db', function (Request $request, Response $response, array $args) use ($db) {
    try {
        $conn = $db->getConnection();
        $result = $conn->fetchAssociative('SELECT "Conexão bem-sucedida!" AS mensagem');
        $response->getBody()->write($result['mensagem']);
    } catch (\Exception $e) {
        $response->getBody()->write('Erro na conexão: ' . $e->getMessage());
    }
    return $response;
});

// Rotas do carrinho de compras
$app->post('/carrinho/adicionar', function (Request $request, Response $response, array $args) use ($db) {
    $controller = new CartController($db);
    return $controller->addItem($request, $response);
});

$app->get('/carrinho', function (Request $request, Response $response, array $args) use ($db) {
    $controller = new CartController($db);
    return $controller->listItems($request, $response);
});

$app->delete('/carrinho/remover/{id}', function (Request $request, Response $response, array $args) use ($db) {
    $controller = new CartController($db);
    return $controller->removeItem($request, $response, $args);
});

$app->post('/carrinho/finalizar', function (Request $request, Response $response, array $args) use ($db) {
    $controller = new CartController($db);
    return $controller->finalizeCart($request, $response);
});

// Rodar a aplicação
$app->run();