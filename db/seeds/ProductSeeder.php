<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class ProductSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Product 1',
                'description' => 'Description for product 1',
                'price' => 10.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Product 2',
                'description' => 'Description for product 2',
                'price' => 20.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Product 3',
                'description' => 'Description for product 3',
                'price' => 30.00,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('products')->insert($data)->saveData();
    }
}