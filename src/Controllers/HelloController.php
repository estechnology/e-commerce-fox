<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HelloController {
    public function index(Request $request, Response $response): Response {
        // Configurar o TWIG
        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $twig = new Environment($loader);

        // Renderizar o template com uma variável
        $html = $twig->render('hello.twig', ['mensagem' => 'Hello, World com TWIG!']);

        $response->getBody()->write($html);
        return $response;
    }
}
