<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProductsAndCartItemsTables extends AbstractMigration
{
    public function change(): void
    {
        // Criação da tabela products
        $products = $this->table('products');
        $products->addColumn('name', 'string', ['limit' => 100])
                 ->addColumn('description', 'text', ['null' => true])
                 ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
                 ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                 ->addIndex(['name']) // Índice na coluna name para melhorar a performance das consultas
                 ->create();

        // Criação da tabela cart
        $cart = $this->table('cart');
        $cart->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
             ->create();         

        // Criação da tabela cart_items
        $cartItems = $this->table('cart_items');
        $cartItems->addColumn('cart_id', 'integer')
                  ->addColumn('product_id', 'integer')
                  ->addColumn('quantity', 'integer')
                  ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
                  ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                  ->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION']) // Chave estrangeira para a tabela products
                  ->addForeignKey('cart_id', 'cart', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION']) // Chave estrangeira para a tabela cart
                  ->addIndex(['cart_id']) // Índice na coluna product_id para melhorar a performance das consultas
                  ->create();
    }
}