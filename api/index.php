<?php

// Direct request file statis jika ada
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && file_exists(__DIR__ . '/../public' . $uri)) {
    return false;
}

// Teruskan request utama ke public/index.php Laravel
require __DIR__ . '/../public/index.php';