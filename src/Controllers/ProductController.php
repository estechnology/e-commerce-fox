<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Models\Product;

class ProductController
{
    private $productModel;
    private $db;

    public function __construct(Product $productModel, $db)
    {
        $this->productModel = $productModel;
        $this->db = $db;
    }

    public function listProducts(Request $request, Response $response): Response
    {
        $products = $this->productModel->getAllProducts();

        $loader = new FilesystemLoader([
            __DIR__ . '/../Views/product', // Diretório atual de templates de produto
            __DIR__ . '/../Views/cart',    // Diretório onde 'base.twig' está localizado
        ]);
        $twig = new Environment($loader);

        $html = $twig->render('list_products.twig', [
            'products' => $products,
        ]);

        $response->getBody()->write($html);
        return $response;
    }
}
