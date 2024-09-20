<?php
return [
    'default' => env('CACHE_DRIVER', 'file'),
    'stores' => [
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache'),
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],
    ],
    'prefix' => 'lumen_cache',
];