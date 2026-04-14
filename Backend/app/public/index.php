<?php
$allowedOrigin = getenv("APP_CORS_ORIGIN") ?: "http://localhost:5173";
$allowedOrigin = trim($allowedOrigin, " \t\n\r\0\x0B\"'");
header("Access-Control-Allow-Origin: " . $allowedOrigin);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

error_reporting(E_ALL);
$appEnv = getenv("APP_ENV") ?: "development";
$appEnv = trim($appEnv, " \t\n\r\0\x0B\"'");
ini_set("display_errors", $appEnv === "development" ? "1" : "0");
ini_set("log_errors", "1");

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    header("Content-Type: application/json; charset=utf-8");
    http_response_code(500);
    echo json_encode(["errorMessage" => "Composer autoload not found. Run composer install."]);
    exit;
}
require $autoloadPath;

// Helper: wraps controller dispatch in a closure so class-loading failures
// throw a catchable exception instead of silently skipping the handler
// (Bramus Router calls class_exists() and does nothing on false — this bypasses that).
function c(string $class, string $method): callable {
    return function () use ($class, $method) {
        $fqn = 'Controllers\\' . $class;
        (new $fqn())->{$method}(...func_get_args());
    };
}

try {
    $router = new \Bramus\Router\Router();

    $router->set404(function () {
        header("Content-Type: application/json; charset=utf-8");
        http_response_code(404);
        echo json_encode(["errorMessage" => "Route not found"]);
    });

    // products
    $router->get('/products',          c('ProductController', 'getAll'));
    $router->get('/products/(\d+)',    c('ProductController', 'getOne'));
    $router->post('/products',         c('ProductController', 'create'));
    $router->put('/products/(\d+)',    c('ProductController', 'update'));
    $router->delete('/products/(\d+)', c('ProductController', 'delete'));

    // categories
    $router->get('/categories',          c('CategoryController', 'getAll'));
    $router->get('/categories/(\d+)',    c('CategoryController', 'getOne'));
    $router->post('/categories',         c('CategoryController', 'create'));
    $router->put('/categories/(\d+)',    c('CategoryController', 'update'));
    $router->delete('/categories/(\d+)', c('CategoryController', 'delete'));

    // users
    $router->post('/users/login',    c('UserController', 'login'));
    $router->post('/users/refresh',  c('UserController', 'refresh'));
    $router->post('/users/signup',   c('UserController', 'signup'));

    // cart
    $router->get('/cart',               c('CartController', 'getCart'));
    $router->post('/cart',              c('CartController', 'addToCart'));
    $router->delete('/cart/(\d+)',      c('CartController', 'removeFromCart'));
    $router->delete('/cart',            c('CartController', 'clearCart'));
    $router->delete('/cart/cleanup',    c('CartController', 'cleanupZeroStock'));
    $router->put('/cart',               c('CartController', 'updateQuantity'));

    // orders / checkout
    $router->get('/orders',             c('OrderController', 'getUserOrders'));
    $router->post('/cart/checkout',     c('CartController', 'checkout'));
    $router->delete('/cart/checkout',   c('CartController', 'checkout'));

    // admin
    $router->get('/admin/users',           c('UserController', 'getAllUsers'));
    $router->put('/admin/users/(\d+)',     c('UserController', 'updateUserRole'));

    $router->run();
} catch (\Throwable $e) {
    error_log($e->__toString());
    header("Content-Type: application/json; charset=utf-8");
    http_response_code(500);
    echo json_encode(["errorMessage" => "Internal server error: " . $e->getMessage()]);
}
