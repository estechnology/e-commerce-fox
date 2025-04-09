<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Models\Cart;  // Importando a Model Cart

class CartController
{
    private $cartModel;
    private $db;

    public function __construct(Cart $cartModel, $db)
    {
        $this->cartModel = $cartModel;
        $this->db = $db;
    }


    public function createCart(Request $request, Response $response): Response
    {
        // 1. Verificar se existe um carrinho pendente (status_id = 1) sem itens
        $existingPendingCartId = $this->cartModel->findEmptyPendingCart();

        if ($existingPendingCartId) {
            // 2. Se existir, retornar o ID desse carrinho existente
            $response->getBody()->write(json_encode(['cart_id' => $existingPendingCartId]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            // 3. Se não existir, criar um novo carrinho
            $cartId = $this->cartModel->createCart();
            $response->getBody()->write(json_encode(['cart_id' => $cartId]));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function getCart(Request $request, Response $response, array $args): Response
    {
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

    public function updateCart(Request $request, Response $response, array $args): Response
    {
        $cartId = $args['cart_id'];
        $data = $request->getParsedBody();
        $conn = $this->db->getConnection();

        $conn->update('cart', $data, ['id' => $cartId]);

        $response->getBody()->write('Carrinho atualizado');
        return $response;
    }

    public function deleteCart(Request $request, Response $response, array $args): Response
    {
        $cartId = $args['cart_id'];
        $conn = $this->db->getConnection();

        $conn->delete('cart', ['id' => $cartId]);

        $response->getBody()->write('Carrinho removido');
        return $response;
    }

    // Método alterado para utilizar a Model Cart e seguir o padrão MVC
    public function addItem(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $cartId = $data['cart_id'];
        $productId = $data['product_id'];
        $quantity = $data['quantity'];
        $price = $data['price'];

        $result = $this->cartModel->addItem($cartId, $productId, $quantity, $price);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function listItems(Request $request, Response $response, array $args): Response
    {
        $cartId = $args['cart_id'];
        $conn = $this->db->getConnection();
        $items = $conn->fetchAllAssociative('SELECT ci.*, p.name, p.description FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?', [$cartId]);

        $response->getBody()->write(json_encode($items));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function removeItem(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $conn = $this->db->getConnection();
        $conn->delete('cart_items', ['id' => $id]);

        $response->getBody()->write('Item removido do carrinho');
        return $response;
    }

    public function finalizeCart(Request $request, Response $response, array $args): Response
    {
        $cartId = $args['cart_id'];
        $conn = $this->db->getConnection();

        // Obter os itens do carrinho para exibir na confirmação (opcional)
        $items = $conn->fetchAllAssociative('SELECT ci.*, p.name, p.description FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?', [$cartId]);

        // Calcular o total da compra
        $total = $conn->fetchOne('SELECT SUM(quantity * price) FROM cart_items WHERE cart_id = ?', [$cartId]);

        // Atualizar o status do carrinho para 'COMPLETED' (assumindo que o ID para 'COMPLETED' seja 3)
        $statusAtualizado = $this->cartModel->updateCartStatus($cartId, 3); // Use o método do Model

        if ($statusAtualizado) {
            $loader = new FilesystemLoader(__DIR__ . '/../Views/cart');
            $twig = new Environment($loader);

            $html = $twig->render('finalizar_compra.twig', [
                'cart_id' => $cartId,
                'total' => $total,
                'items' => $items,
                'finalizado' => true, // Adicione uma variável para indicar que o carrinho foi finalizado
            ]);

            $response->getBody()->write($html);
            return $response;
        } else {
            // Tratar o caso em que a atualização do status falha
            $response->getBody()->write('Erro ao finalizar o carrinho.');
            return $response->withStatus(500); // Ou outro código de erro apropriado
        }
    }

    public function listItemsTwig(Request $request, Response $response, array $args): Response
    {
        $cartId = $args['cart_id'];

        if (!$cartId) {
            $response->getBody()->write('Erro: ID do carrinho não foi fornecido.');
            return $response->withStatus(400);
        }

        // Atualizar o status do carrinho para "PROCESSING" (status ID 2)
        $statusAtualizado = $this->cartModel->updateCartStatus($cartId, 2);

        if (!$statusAtualizado) {
            // Log de erro ou tratamento específico caso a atualização falhe
            error_log("Falha ao atualizar o status do carrinho " . $cartId . " para PROCESSING.");
            // Você pode decidir se quer exibir um erro ao usuário ou apenas logar.
        }

        $loader = new FilesystemLoader(__DIR__ . '/../Views/cart');
        $twig = new Environment($loader);

        $items = $this->cartModel->getCartItems($cartId);
        $total = $this->cartModel->getTotalCart($cartId);
        $products = $this->cartModel->getAllProducts(); // Você pode precisar ou não desta linha aqui

        $html = $twig->render('items.twig', [
            'cart_id' => $cartId,
            'items' => $items,
            'total' => $total,
            'products' => $products // Passa a lista de produtos para a view
        ]);

        $response->getBody()->write($html);
        return $response;
    }


    public function removeItemWithConfirmation(Request $request, Response $response, array $args): Response
    {
        $itemId = $args['id'];

        $conn = $this->db->getConnection();
        $item = $conn->fetchAssociative('SELECT * FROM cart_items WHERE id = ?', [$itemId]);

        if (!$item) {
            $response->getBody()->write('Erro: Item não encontrado.');
            return $response->withStatus(404);
        }

        // Deletar o item do banco de dados
        $conn->delete('cart_items', ['id' => $itemId]);

        // Retornar uma mensagem de sucesso
        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Item removido com sucesso.'
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function showAddItemForm(Request $request, Response $response, array $args): Response
    {
        $cartId = $args['cart_id'];

        if (!$cartId) {
            $response->getBody()->write('Erro: ID do carrinho não foi fornecido.');
            return $response->withStatus(400);
        }

        $loader = new FilesystemLoader(__DIR__ . '/../Views/cart');
        $twig = new Environment($loader);

        // Obtém a lista de produtos da Model
        $products = $this->cartModel->getAllProducts();

        $html = $twig->render('add_item.twig', [
            'cart_id' => $cartId,
            'products' => $products // Passa a lista de produtos para a view
        ]);

        $response->getBody()->write($html);
        return $response;
    }
}
