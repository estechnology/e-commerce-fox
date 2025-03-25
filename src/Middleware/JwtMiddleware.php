<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Response as SlimResponse;

class JwtMiddleware
{
    private $secret;
    private $algorithm;

    public function __construct(array $jwtConfig)
    {
        $this->secret = $jwtConfig['secret'];
        $this->algorithm = $jwtConfig['algorithm'];
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $authorizationHeader = $request->getHeaderLine('Authorization');

        if (!$authorizationHeader) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(['error' => 'Token não fornecido.']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        try {
            $token = str_replace('Bearer ', '', $authorizationHeader);
            $decoded = JWT::decode($token, new Key($this->secret, $this->algorithm));
            $request = $request->withAttribute('decoded_token', $decoded);
            return $handler->handle($request);
        } catch (\Exception $e) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(['error' => 'Token inválido: ' . $e->getMessage()]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
    }
}
