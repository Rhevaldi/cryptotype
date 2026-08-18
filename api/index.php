<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Alihkan folder storage & views Laravel ke /tmp (folder writable di Vercel)
$_ENV['APP_STORAGE_PATH'] = '/tmp/storage';

$directories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Override path storage di instance aplikasi
$app->useStoragePath('/tmp/storage');

$app->handleRequest(Request::capture());