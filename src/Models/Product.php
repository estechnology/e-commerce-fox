<?php

namespace App\Models;

class Product
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllProducts()
    {
        $conn = $this->db->getConnection();
        return $conn->fetchAllAssociative('SELECT id, name, description, price FROM products');
    }
}
