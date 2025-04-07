<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Database;

class CartController {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function createCart(Request $request, Response $response): Response {
        $conn = $this->db->getConnection();
        $conn->insert('cart', ['created_at' => date('Y-m-d H:i:s')]);
        $cartId = $conn->lastInsertId();

        $response->getBody()->write(json_encode(['cart_id' => $cartId]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getCart(Request $request, Response $response, array $args): Response {
        $cartId = $args['cart_id'];
        $conn = $this->db->getConnection();
        $cart = $conn->fetchAssociative('SELECT * FROM cart WHERE id = ?', [$cartId]);

        if (!$cart) {
            $response->getBody()->write('Carrinho não encontrado');
            return $response->withStatus(404);
        }

        $response->getBody()->write(json_encode($cart));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function updateCart(Request $request, Response $response, array $args): Response {
        $cartId = $args['cart_id'];
        $data = $request->getParsedBody();
        $conn = $this->db->getConnection();

        $conn->update('cart', $data, ['id' => $cartId]);

        $response->getBody()->write('Carrinho atualizado');
        return $response;
    }

    public function deleteCart(Request $request, Response $response, array $args): Response {
        $cartId = $args['cart_id'];
        $conn = $this->db->getConnection();

        $conn->delete('cart', ['id' => $cartId]);

        $response->getBody()->write('Carrinho removido');
        return $response;
    }

    public function addItem(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $cartId = $data['cart_id'];
        $productId = $data['product_id'];
        $quantity = $data['quantity'];
        $price = $data['price'];

        $conn = $this->db->getConnection();

        // Verificar se o carrinho existe
        $cart = $conn->fetchAssociative('SELECT * FROM cart WHERE id = ?', [$cartId]);
        if (!$cart) {
            $response->getBody()->write('Carrinho não encontrado');
            return $response->withStatus(404);
        }

        // Verificar se o produto existe
        $product = $conn->fetchAssociative('SELECT * FROM products WHERE id = ?', [$productId]);
        if (!$product) {
            $response->getBody()->write('Produto não encontrado');
            return $response->withStatus(404);
        }

        $conn->insert('cart_items', [
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $response->getBody()->write('Item adicionado ao carrinho');
        return $response;
    }

    public function listItems(Request $request, Response $response, array $args): Response {
        $cartId = $args['cart_id'];
        $conn = $this->db->getConnection();
        $items = $conn->fetchAllAssociative('SELECT ci.*, p.name, p.description FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?', [$cartId]);

        $response->getBody()->write(json_encode($items));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function removeItem(Request $request, Response $response, array $args): Response {
        $id = $args['id'];

        $conn = $this->db->getConnection();
        $conn->delete('cart_items', ['id' => $id]);

        $response->getBody()->write('Item removido do carrinho');
        return $response;
    }

    public function finalizeCart(Request $request, Response $response, array $args): Response {
        $cartId = $args['cart_id'];
        $conn = $this->db->getConnection();
        $total = $conn->fetchOne('SELECT SUM(quantity * price) FROM cart_items WHERE cart_id = ?', [$cartId]);

        $response->getBody()->write('Total da compra: ' . $total);
        return $response;
    }

    public function listItemsTwig(Request $request, Response $response): Response {
        // Configurar o TWIG
        $loader = new FilesystemLoader(__DIR__ . '/../Views/cart/');
        $twig = new Environment($loader);

        // Exemplo de consulta ao banco de dados
        $conn = $this->db->getConnection();
        $result = $conn->fetchAssociative('SELECT "Mensagem sendo enviada para a view pela controller" AS mensagem');

        // Renderizar o template com uma variável
        $html = $twig->render('items.twig', ['mensagem' => $result['mensagem']]);

        $response->getBody()->write($html);
        return $response;
    }
}