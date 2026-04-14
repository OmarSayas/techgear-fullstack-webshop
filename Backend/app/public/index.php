<?php
$allowedOrigin = getenv("APP_CORS_ORIGIN") ?: "http://localhost:5173";
header("Access-Control-Allow-Origin: " . $allowedOrigin);
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

error_reporting(E_ALL);
$appEnv = getenv("APP_ENV") ?: "development";
ini_set("display_errors", $appEnv === "development" ? "1" : "0");

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

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
$router->run();
