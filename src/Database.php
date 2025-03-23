<?php
namespace App;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;

class Database {
    private $connection;

    public function __construct(array $config) {
        try {
            $connectionParams = [
                'dbname' => $config['dbname'],
                'user' => $config['user'],
                'password' => $config['password'],
                'host' => $config['host'],
                'driver' => $config['driver'],
                'pooling' => $config['pooling'],
                'pool_size' => $config['pool_size'],
            ];
            $this->connection = DriverManager::getConnection($connectionParams);
        } catch (Exception $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}