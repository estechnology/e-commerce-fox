<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'username' => 'admin',
                'password' => password_hash('admin@123!', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'user',
                'password' => password_hash('user@123!', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('users')->insert($data)->saveData();
    }
}