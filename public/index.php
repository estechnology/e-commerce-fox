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
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$config = require __DIR__ . '/../config/config.php';
$jwtConfig = require __DIR__ . '/../config/jwt.php';
$db = new Database($config['db']);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$container = $app->getContainer();

// Rota para autenticação do token.
$app->post('/auth', function (Request $request, Response $response) use ($jwtConfig, $db) {
    $controller = new AuthController($jwtConfig, $db);
    return $controller->generateToken($request, $response);
});

// Rota para criar um novo carrinho (PÚBLICA)
// Rotas protegidas
$app->group('/carrinho', function (\Slim\Routing\RouteCollectorProxy $group) use ($db) {
    // Obter carrinho por ID
    $group->get('/{cart_id}', function (Request $request, Response $response, array $args) use ($db) {
        $cartModel = new \App\Models\Cart($db);   // Instanciando o Cart Model
        $controller = new CartController($cartModel, $db);   // Passando o Cart Model e o db para o controller
        return $controller->getCart($request, $response, $args);
    });

    // Atualizar carrinho
    $group->put('/{cart_id}', function (Request $request, Response $response, array $args) use ($db) {
        $cartModel = new \App\Models\Cart($db);   // Instanciando o Cart Model
        $controller = new CartController($cartModel, $db);   // Passando o Cart Model e o db para o controller
        return $controller->updateCart($request, $response, $args);
    });

    // Deletar carrinho
    $group->delete('/{cart_id}', function (Request $request, Response $response, array $args) use ($db) {
        $cartModel = new \App\Models\Cart($db);   // Instanciando o Cart Model
        $controller = new CartController($cartModel, $db);   // Passando o Cart Model e o db para o controller
        return $controller->deleteCart($request, $response, $args);
    });

    // Listar itens do carrinho
    $group->get('/{cart_id}/itens', function (Request $request, Response $response, array $args) use ($db) {
        $cartModel = new \App\Models\Cart($db);   // Instanciando o Cart Model
        $controller = new CartController($cartModel, $db);   // Passando o Cart Model e o db para o controller
        return $controller->listItems($request, $response, $args);
    });

    // Remover item do carrinho
    $group->delete('/remover/{id}', function (Request $request, Response $response, array $args) use ($db) {
        $cartModel = new \App\Models\Cart($db);   // Instanciando o Cart Model
        $controller = new CartController($cartModel, $db);   // Passando o Cart Model e o db para o controller
        return $controller->removeItem($request, $response, $args);
    });
});

// Rotas públicas (Autenticação e Produtos)
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Bem vindo a API de Carrinho de Compras, aqui você pode criar, atualizar e deletar carrinhos de compras, o projeto foi desenvolvido no Framework Slim e Twig. Qualquer dúvida entre em contato com o desenvolvedor => Eduardo Sampaio (17)98189-6773 ");
    return $response;
});
$app->post('/carrinho', function (Request $request, Response $response) use ($db) {
    $cartModel = new \App\Models\Cart($db);   // Instanciando o Cart Model
    $controller = new CartController($cartModel, $db);   // Passando o Cart Model e o db para o controller
    return $controller->createCart($request, $response);
});

$app->get('/produtos', function (Request $request, Response $response) use ($db) {
    $productModel = new \App\Models\Product($db); // Crie uma Product Model
    $controller = new \App\Controllers\ProductController($productModel, $db); // Crie um Product Controller
    return $controller->listProducts($request, $response);
});

$app->post('/carrinho/adicionar', function (Request $request, Response $response) use ($db) {
    $cartModel = new \App\Models\Cart($db);
    $controller = new CartController($cartModel, $db);
    return $controller->addItem($request, $response);
});

$app->post('/carrinho/finalizar/{cart_id}', function (Request $request, Response $response, array $args) use ($db) {
    $cartModel = new \App\Models\Cart($db);
    $controller = new CartController($cartModel, $db);
    return $controller->finalizeCart($request, $response, $args);
});

$app->get('/hello-twig', function (Request $request, Response $response, array $args) use ($db) {
    $controller = new HelloController($db);
    return $controller->index($request, $response);
});

$app->get('/{cart_id}/list-items-twig', function (Request $request, Response $response, array $args) use ($db) {
    $cartModel = new \App\Models\Cart($db);
    $controller = new CartController($cartModel, $db);
    return $controller->listItemsTwig($request, $response, $args);
});

$app->delete('/cart/remove/{id}', function (Request $request, Response $response, array $args) use ($db) {
    $cartModel = new \App\Models\Cart($db);
    $controller = new CartController($cartModel, $db);
    return $controller->removeItemWithConfirmation($request, $response, $args);
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
