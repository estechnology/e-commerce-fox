<?php
namespace App;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;

class Database {
    private $connection;

    public function __construct(array $config) {
        try {
            $this->connection = DriverManager::getConnection($config);
        } catch (Exception $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}