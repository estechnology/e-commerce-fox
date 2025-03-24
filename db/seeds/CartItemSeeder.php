<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CartItemSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'cart_id' => 1, // Certifique-se de que este ID existe na tabela cart
                'product_id' => 1, // Certifique-se de que este ID existe na tabela products
                'quantity' => 2,
                'price' => 10.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'cart_id' => 1, // Certifique-se de que este ID existe na tabela cart
                'product_id' => 2, // Certifique-se de que este ID existe na tabela products
                'quantity' => 1,
                'price' => 20.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'cart_id' => 2, // Certifique-se de que este ID existe na tabela cart
                'product_id' => 3, // Certifique-se de que este ID existe na tabela products
                'quantity' => 3,
                'price' => 30.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('cart_items')->insert($data)->saveData();
    }
}