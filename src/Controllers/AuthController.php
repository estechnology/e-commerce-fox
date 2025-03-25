<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;

class AuthController
{
    private $secret;
    private $algorithm;

    public function __construct(array $jwtConfig)
    {
        $this->secret = $jwtConfig['secret'];
        $this->algorithm = $jwtConfig['algorithm'];
    }

    public function generateToken(Request $request, Response $response): Response
    {
        $body = (string) $request->getBody();
        $data = json_decode($body, true);

        if (!isset($data['username']) || !isset($data['password'])) {
            $response->getBody()->write(json_encode(['error' => 'Credenciais ausentes.']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        if ($data['username'] === 'admFox' && $data['password'] === 'ixFezwFgT^rY@a&pf%2q$@') {
            $payload = [
                'username' => $data['username'],
                'exp' => time() + 3600,
            ];

            $token = JWT::encode($payload, $this->secret, $this->algorithm);

            $response->getBody()->write(json_encode(['token' => $token]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Credenciais inválidas.']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
    }
}
