<?php

$publicPath = __DIR__ . '/public';
if (!is_dir($publicPath)) {
    $publicPath = __DIR__ . '/app/public';
}

if (!is_dir($publicPath)) {
    http_response_code(500);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(["errorMessage" => "Public directory not found"]);
    exit;
}
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$resolvedPath = realpath($publicPath . $requestPath);

if (
    $requestPath !== '/'
    && $resolvedPath !== false
    && str_starts_with($resolvedPath, realpath($publicPath))
    && is_file($resolvedPath)
) {
    return false;
}

require $publicPath . '/index.php';
