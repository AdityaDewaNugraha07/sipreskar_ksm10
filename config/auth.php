<?php

return [
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'karangtaruna'), // Pintu utamanya Karang Taruna
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'karangtaruna' => [
            'driver' => 'session',
            'provider' => 'karang_taruna',
        ],
        'pembina' => [ // Pintu masuk untuk Pembina
            'driver' => 'session',
            'provider' => 'pembinas',
        ],
    ],

    'providers' => [
        'karang_taruna' => [
            'driver' => 'eloquent',
            'model' => App\Models\KarangTaruna::class,
        ],
        'pembinas' => [ // Model Pembina
            'driver' => 'eloquent',
            'model' => App\Models\Pembina::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'karang_taruna',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];