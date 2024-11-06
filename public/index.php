<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';

$app = AppFactory::create();
// Untuk di sisi Produksi cache ini nanti dipindahken ke folder tertentu (baca dokumentasi lagi sebelum di deploy di server)
$twig = Twig::create(__DIR__ . '/../src/templates', ['cache' => false]); 

$app->addBodyParsingMiddleware();
$app->add(TwigMiddleware::create($app, $twig));

$app->get('/', function(Request $request, Response $response){
    $view = Twig::fromRequest($request);

    return $view->render($response, 'login.php.twig');
});

$app->post('/login', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody(); // Ini buat ambil data dari form login dimana disimpen di Array Assoc Data
    $username = $data['username']; // Ini nanti ganti jadi email
    $password = $data['password'];

    // Ini buat nyari username di database dari Model User yang dibuat sebelumnya
    // Model User ada di src/Models/User.php; (App\Models\User)
    $user = User::where('username', $username)->first();

    $view = Twig::fromRequest($request);

    if($user && password_verify($password, $user->password)) {
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['role_id'] = $user->role_id;

        return $view->render($response, 'admin.php.twig');
    }
    else{
        return $view->render($response, 'err/401.html.twig');
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

// Dashboard Role
$app->get('/dashboard/admin', function(Request $request, Response $response, $args){

})->add(new RoleMiddleware(1));

$app->run();