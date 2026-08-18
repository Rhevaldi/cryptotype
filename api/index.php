<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Layani berkas statis/favicon jika ada
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
if ($uri !== '/' && file_exists(__DIR__ . '/../public' . $uri)) {
    return false;
}

// 2. Siapkan direktori writable di /tmp
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

// 3. Set environment variable darurat
putenv("APP_STORAGE_PATH={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("CACHE_STORE=array");
putenv("SESSION_DRIVER=cookie");

if (!getenv('APP_KEY')) {
    putenv("APP_KEY=base64:Ng47AWRkBEnNJLUxO3BrPop7gUTFARilAPmZc/jIP0A=");
}

// 4. Autoload Composer
require __DIR__ . '/../vendor/autoload.php';

// 5. Bootstrap Aplikasi
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 6. Tangani Request menggunakan Kernel/Runner bawaan
$kernel = $app->make(Kernel::class);
$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);