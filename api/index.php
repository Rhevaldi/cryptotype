<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Tangani file statis & favicon secara langsung agar tidak melempar 500
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
if ($uri !== '/' && file_exists(__DIR__ . '/../public' . $uri)) {
    return false;
}

// 2. Buat folder temporary wajib di /tmp
$storagePath = '/tmp/storage';
$directories = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 3. Set path environment & view compilation
$_ENV['APP_STORAGE_PATH'] = $storagePath;
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

// 4. Autoload Composer
require __DIR__ . '/../vendor/autoload.php';

// 5. Bootstrap Laravel Application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bind storage path secara eksplisit
$app->useStoragePath($storagePath);

// 6. Tangani Request & Kirim Response
$request = Request::capture();
$response = $app->handleRequest($request);
$response->send();