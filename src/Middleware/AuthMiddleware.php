<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as SlimResponse;

class AuthMiddleware {
    public function __invoke(Request $request, Response $response, $next) {
        session_start();

        if(!isset($_SESSION['user_id'])) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode(['error' => 'Unathorized']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        return $next($request, $response);
    }
}