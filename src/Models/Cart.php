<?php

namespace App\Models;

use App\Database;

class Cart
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getCartById(int $cartId): ?array
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT * FROM cart WHERE id = :id');
        $stmt->bindValue('id', $cartId);
        $result = $stmt->executeQuery()->fetchAssociative();
        return $result ?: null;
    }

    public function getProductById(int $productId): ?array
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT id, name, description, price FROM products WHERE id = :id');
        $stmt->bindValue('id', $productId);
        $result = $stmt->executeQuery()->fetchAssociative();
        return $result ?: null;
    }

    public function getCartItems(int $cartId): array
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('
            SELECT ci.id, ci.quantity, ci.price, p.name, p.description, ci.product_id
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = :cartId
        ');
        $stmt->bindValue('cartId', $cartId);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function addItem(int $cartId, int $productId, int $quantity, float $price): array
    {
        $cart = $this->getCartById($cartId);
        if (!$cart) {
            return ['success' => false, 'message' => 'Carrinho não encontrado'];
        }

        $product = $this->getProductById($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'Produto não encontrado'];
        }

        $conn = $this->db->getConnection();
        $conn->insert('cart_items', [
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price,
            'created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);
        $itemId = $conn->lastInsertId();

        $stmt = $conn->prepare('
            SELECT ci.id, ci.quantity, ci.price, p.name, p.description, ci.product_id
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.id = :itemId
        ');
        $stmt->bindValue('itemId', $itemId);
        $newItem = $stmt->executeQuery()->fetchAssociative();

        return ['success' => true, 'message' => 'Produto adicionado', 'item' => $newItem];
    }

    public function removeItemFromCart(int $itemId): bool
    {
        $conn = $this->db->getConnection();
        return $conn->delete('cart_items', ['id' => $itemId]);
    }

    public function getTotalCart(int $cartId): float
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT SUM(quantity * price) FROM cart_items WHERE cart_id = :cartId');
        $stmt->bindValue('cartId', $cartId);
        $total = $stmt->executeQuery()->fetchOne();
        return $total ?: 0.00;
    }

    public function getAllProducts(): array
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT id, name, price FROM products');
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function findEmptyPendingCart(): ?int
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('
            SELECT c.id
            FROM cart c
            WHERE c.status_id = :statusId
            AND NOT EXISTS (SELECT 1 FROM cart_items ci WHERE ci.cart_id = c.id)
            LIMIT 1
        ');
        $stmt->bindValue('statusId', 1);
        $result = $stmt->executeQuery()->fetchOne();
        return $result ? (int) $result : null;
    }

    public function createCart(int $initialStatusId = 1): int
    {
        $conn = $this->db->getConnection();
        $conn->insert('cart', [
            'created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            'status_id' => $initialStatusId,
        ]);
        return $conn->lastInsertId();
    }

    public function updateCartStatus(int $cartId, int $statusId): bool
    {
        $conn = $this->db->getConnection();
        $affectedRows = $conn->update(
            'cart',
            ['status_id' => $statusId, 'updated_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s')],
            ['id' => $cartId]
        );
        return $affectedRows > 0;
    }
}
