<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Database;

class HelloController {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function index(Request $request, Response $response): Response {
        // Configurar o TWIG
        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $twig = new Environment($loader);

        // Exemplo de consulta ao banco de dados
        $conn = $this->db->getConnection();
        $result = $conn->fetchAssociative('SELECT "Hello, World com TWIG e MySQL!" AS mensagem');

        // Renderizar o template com uma variável
        $html = $twig->render('hello.twig', ['mensagem' => $result['mensagem']]);

        $response->getBody()->write($html);
        return $response;
    }
}