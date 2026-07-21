<?php

declare(strict_types=1);

return [
    'disk_name' => env('MEDIA_DISK', 'public'),
    'queue_connection_name' => env('QUEUE_CONNECTION', 'sync'),
    'queue_name' => env('MEDIA_QUEUE', ''),
    'queue_suffix' => env('MEDIA_QUEUE_SUFFIX', ''),

    'default_locale' => 'en',
    'url_generator' => null,

    // Custom Media model to use spatie_media table instead of conflicting media table
    'media_model' => App\Models\SpatieMedia::class,

    'path_generator' => null,

    'default_file_url_prefix' => env('MEDIA_URL', ''),

    'consider_symlinks' => env('MEDIA_CONSIDER_SYMLINKS', false),

    'update_generated_files' => env('MEDIA_UPDATE_GENERATED_FILES', true),

    'uuids' => [
        'auto_generate_ids' => true,
    ],

    'remote' => [
        'extra_headers' => [
            'CacheControl' => 'max-age=604800',
        ],
    ],

    'responsive_images' => [
        'enabled' => false,
        'use_default_factors' => true,
        'width_factors' => [2.5, 2, 1.5, 1],
        'height_factors' => [2.5, 2, 1.5, 1],
    ],

    'enable_vapor' => env('MEDIA_ENABLE_VAPOR', false),

    'temporary_url_enabled' => env('MEDIA_TEMPORARY_URL_ENABLED', false),
];
