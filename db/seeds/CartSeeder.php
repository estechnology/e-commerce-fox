<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CartSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('cart')->insert($data)->saveData();
    }
}