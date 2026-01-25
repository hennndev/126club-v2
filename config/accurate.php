<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Accurate Sync Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk sinkronisasi data dari Accurate ke database lokal
    |
    */

    // Interval sync otomatis dalam jam (1, 2, 3, dst)
    'sync_interval_hours' => env('ACCURATE_SYNC_INTERVAL_HOURS', 2),

    // Interval sync dalam MENIT (untuk testing/development)
    // Jika di-set, akan override sync_interval_hours
    'sync_interval_minutes' => env('ACCURATE_SYNC_INTERVAL_MINUTES', null),

    // Enable/disable auto sync
    'auto_sync_enabled' => env('ACCURATE_AUTO_SYNC_ENABLED', true),

    // Batch size untuk sync
    'sync_batch_size' => env('ACCURATE_SYNC_BATCH_SIZE', 100),

    // Timeout untuk request ke Accurate API (dalam detik)
    'api_timeout' => env('ACCURATE_API_TIMEOUT', 30),

    // Entities yang akan di-sync
    'sync_entities' => [
        'items' => [
            'enabled' => true,
            'schedule' => '2', // dalam jam
        ],
        'customers' => [
            'enabled' => true,
            'schedule' => '3',
        ],
        'sales_orders' => [
            'enabled' => true,
            'schedule' => '1',
        ],
        'purchase_orders' => [
            'enabled' => true,
            'schedule' => '1',
        ],
    ],
];
