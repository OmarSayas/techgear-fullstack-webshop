<?php

$publicPath = __DIR__ . '/public';
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
