<?php
require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Controllers\HelloController;
use App\Controllers\CartController;
use App\Controllers\AuthController;
use App\Database;
use App\Middleware\JwtMiddleware;

$config = require __DIR__ . '/../config/config.php';
$jwtConfig = require __DIR__ . '/../config/jwt.php';
$db = new Database($config['db']);

$app = AppFactory::create();

// Rota para autenticação do token.
$app->post('/auth', function (Request $request, Response $response) use ($jwtConfig, $db) {
    $controller = new AuthController($jwtConfig, $db);
    return $controller->generateToken($request, $response);
});

// Rotas protegidas
$app->group('/carrinho', function (\Slim\Routing\RouteCollectorProxy $group) use ($db) {
    // Criar um novo carrinho
    $group->post('', function (Request $request, Response $response) use ($db) {
        $controller = new CartController($db);
        return $controller->createCart($request, $response);
    });

    // Obter carrinho por ID
    $group->get('/{cart_id}', function (Request $request, Response $response, array $args) use ($db) {
        $controller = new CartController($db);
        return $controller->getCart($request, $response, $args);
    });

    // Atualizar carrinho
    $group->put('/{cart_id}', function (Request $request, Response $response, array $args) use ($db) {
        $controller = new CartController($db);
        return $controller->updateCart($request, $response, $args);
    });

    // Deletar carrinho
    $group->delete('/{cart_id}', function (Request $request, Response $response, array $args) use ($db) {
        $controller = new CartController($db);
        return $controller->deleteCart($request, $response, $args);
    });

    // Adicionar item ao carrinho
    $group->post('/adicionar', function (Request $request, Response $response) use ($db) {
        $controller = new CartController($db);
        return $controller->addItem($request, $response);
    });

    // Listar itens do carrinho
    $group->get('/{cart_id}/itens', function (Request $request, Response $response, array $args) use ($db) {
        $controller = new CartController($db);
        return $controller->listItems($request, $response, $args);
    });

    // Remover item do carrinho
    $group->delete('/remover/{id}', function (Request $request, Response $response, array $args) use ($db) {
        $controller = new CartController($db);
        return $controller->removeItem($request, $response, $args);
    });

    // Finalizar carrinho
    $group->post('/finalizar/{cart_id}', function (Request $request, Response $response, array $args) use ($db) {
        $controller = new CartController($db);
        return $controller->finalizeCart($request, $response, $args);
    });
})->add(new JwtMiddleware($jwtConfig));

// Rotas públicas (Autenticação)
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Bem vindo a API de Carrinho de Compras, aqui você pode criar, atualizar e deletar carrinhos de compras,o projeto foi desenvolvido no Framework Slim e Twig, qualquer dúvida entre em contato com o desenvolvedor => Eduardo Sampaio (17)98189-6773 ");
    return $response;
});

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

// Rodar a aplicação
$app->run();
