<?php
return [
    'default' => env('FILESYSTEM_DISK', 'uploads'),
    'cloud' => env('FILESYSTEM_CLOUD', 's3'),
    'mimes' => env('FILESYSTEM_MIMES', 'pdf,jpeg,jpg,png'),
    'max_size' => env('FILESYSTEM_MAX_SIZE', '2'),
    'max_width' => env('FILESYSTEM_MAX_WIDTH', '1000'),
    'max_height' => env('FILESYSTEM_MAX_HEIGHT', '1000'),
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => app()->runningInConsole() ? '' : url('/') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
        'temp' => [
            'driver' => 'local',
            'root' => storage_path('app/temp'),
            'url' => app()->runningInConsole() ? '' : url('/') . '/temp',
            'visibility' => 'private',
            'throw' => false,
        ],
        'uploads' => [
            'driver' => 'local',
            'root' => storage_path('app/uploads'),
            'url' => app()->runningInConsole() ? '' : url('/') . '/uploads',
            'visibility' => 'private',
            'throw' => false,
        ],
        's3' => [
            'driver' => 's3',
            'root' =>  env('AWS_ROOT'),
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'visibility' => env('AWS_VISIBILITY', 'private'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],
    ],
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
