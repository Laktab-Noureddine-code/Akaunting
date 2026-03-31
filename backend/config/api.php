<?php
use Illuminate\Cache\RateLimiting\Limit;
return [
    'standardsTree' => env('API_STANDARDS_TREE', 'vnd'),
    'subtype' => env('API_SUBTYPE', 'akaunting'),
    'version' => env('API_VERSION', 'v3'),
    'prefix' => env('API_PREFIX', 'api'),
    'domain' => env('API_DOMAIN'),
    'name' => env('API_NAME', 'Akaunting'),
    'conditionalRequest' => env('API_CONDITIONAL_REQUEST', true),
    'strict' => env('API_STRICT', false),
    'debug' => env('API_DEBUG', false),
    'error_format' => [
        'message' => ':message',
        'errors' => ':errors',
        'code' => ':code',
        'status_code' => ':status_code',
        'debug' => ':debug',
    ],
    'middleware' => explode(',', env('API_MIDDLEWARE', 'api')),
];
