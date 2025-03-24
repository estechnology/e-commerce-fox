<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Database;

class CartController {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function addItem(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $productId = $data['product_id'];
        $quantity = $data['quantity'];
        $price = $data['price'];

        $conn = $this->db->getConnection();

        // Verificar se o produto existe
        $product = $conn->fetchAssociative('SELECT * FROM products WHERE id = ?', [$productId]);
        if (!$product) {
            $response->getBody()->write('Produto não encontrado');
            return $response->withStatus(404);
        }

        $conn->insert('cart_items', [
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price,
        ]);

        $response->getBody()->write('Item adicionado ao carrinho');
        return $response;
    }

    public function listItems(Request $request, Response $response): Response {
        $conn = $this->db->getConnection();
        $items = $conn->fetchAllAssociative('SELECT ci.*, p.name, p.description FROM cart_items ci JOIN products p ON ci.product_id = p.id');

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

    public function finalizeCart(Request $request, Response $response): Response {
        $conn = $this->db->getConnection();
        $total = $conn->fetchOne('SELECT SUM(quantity * price) FROM cart_items');

        $response->getBody()->write('Total da compra: ' . $total);
        return $response;
    }
}