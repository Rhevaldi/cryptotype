<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Buat folder temporary di /tmp agar writable oleh Vercel
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

// 2. Set environment path sebelum bootstrap
$_ENV['APP_STORAGE_PATH'] = $storagePath;
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

// 3. Autoload & Bootstrap Application
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Bind Storage Path secara eksplisit di Service Container
$app->useStoragePath($storagePath);

// 5. Tangani Request
$app->handleRequest(Request::capture());