<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';

return [
    'app' => [
        'env' => env('APP_ENV', 'production'),
        'debug' => env('APP_DEBUG', 'false') === 'true',
        'url' => env('APP_URL', 'http://localhost'),
        'key' => env('APP_KEY', ''),
    ],
    'db' => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'name' => env('DB_NAME', 'urbanstep'),
        'user' => env('DB_USER', 'root'),
        'pass' => env('DB_PASS', ''),
    ],
    'session' => [
        'name' => env('SESSION_NAME', 'urbanstep_session'),
    ],
    'stripe' => [
        'public_key' => env('STRIPE_PUBLIC_KEY', ''),
        'secret_key' => env('STRIPE_SECRET_KEY', ''),
    ],
];
