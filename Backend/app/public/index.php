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
if (!is_file($autoloadPath)) {
    $autoloadPath = __DIR__ . '/../../vendor/autoload.php';
}

if (!is_file($autoloadPath)) {
    header("Content-Type: application/json; charset=utf-8");
    http_response_code(500);
    echo json_encode(["errorMessage" => "Composer autoload not found"]);
    exit;
}

require $autoloadPath;

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');
$router->set404(function () {
    header("Content-Type: application/json; charset=utf-8");
    http_response_code(404);
    echo json_encode(["errorMessage" => "Route not found"]);
});

// routes for the products endpoint
$router->get('/products', 'ProductController@getAll');
$router->get('/products/(\d+)', 'ProductController@getOne');
$router->post('/products', 'ProductController@create');
$router->put('/products/(\d+)', 'ProductController@update');
$router->delete('/products/(\d+)', 'ProductController@delete');

// routes for the categories endpoint
$router->get('/categories', 'CategoryController@getAll');
$router->get('/categories/(\d+)', 'CategoryController@getOne');
$router->post('/categories', 'CategoryController@create');
$router->put('/categories/(\d+)', 'CategoryController@update');
$router->delete('/categories/(\d+)', 'CategoryController@delete');

// routes for the users endpoint
$router->post('/users/login', 'UserController@login');
$router->post('/users/refresh', 'UserController@refresh');
$router->post('/users/signup', 'UserController@signup');

// routes for the cart endpoint
$router->get('/cart', 'CartController@getCart');
$router->post('/cart', 'CartController@addToCart');
$router->delete('/cart/(\d+)', 'CartController@removeFromCart');
$router->delete('/cart', 'CartController@clearCart');
$router->delete('/cart/cleanup', 'CartController@cleanupZeroStock');
$router->put('/cart', 'CartController@updateQuantity');

// routes for the orders endpoint
$router->get('/orders', 'OrderController@getUserOrders');
$router->post('/cart/checkout', 'CartController@checkout');
$router->delete('/cart/checkout', 'CartController@checkout');

// routes for the admin endpoint
$router->get('/admin/users', 'UserController@getAllUsers');
$router->put('/admin/users/(\d+)', 'UserController@updateUserRole');



// Run it!
try {
    $router->run();
} catch (\Throwable $e) {
    error_log($e->__toString());
    header("Content-Type: application/json; charset=utf-8");
    http_response_code(500);
    echo json_encode(["errorMessage" => "Internal server error"]);
}
