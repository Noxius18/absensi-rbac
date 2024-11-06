<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';

use App\Middleware\AuthMiddleware;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

// $app->get('/', function($request, $response) {
//     $response->getBody()->write("Hello Slim!");
//     return $response;
// });

$app->get('/', function(Request $request, Response $response){
    $response->getBody()->write(file_get_contents(__DIR__ . '/../src/templates/login.php'));
    return $response;
});

$app->post('/login', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody(); // Ini buat ambil data dari form login dimana disimpen di Array Assoc Data
    $username = $data['username']; // Ini nanti ganti jadi email
    $password = $data['password'];

    // Ini buat nyari username di database dari Model User yang dibuat sebelumnya
    // Model User ada di src/Models/User.php; (App\Models\User)
    $user = User::where('username', $username)->first();

    if($user && password_verify($password, $user->password)) {
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['role_id'] = $user->role_id;

        $response->getBody()->write(json_encode(['message' => 'Login berhasil']));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
    else{
        $response->getBody()->write(json_encode(['error' => "Login gagal"]));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
});

$app->get('/protected-route', function(Request $request, Response $response, $args){
    $response->getBody()->write(json_encode(['message' => 'Selamat Datang']));
    return $response->withHeader('Content-Type', 'application/json');
})->add(new AuthMiddleware);

$app->post('/logout', function(Request $request, Response $response, $args){
    session_start();
    session_unset();
    session_destroy();

    $response->getBody()->write(json_encode(['message' => 'Logout berhasil']));
    return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
});

$app->run();