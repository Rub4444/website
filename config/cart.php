<?php

return [
    'redis' => [
        'enabled' => env('CART_REDIS_ENABLED', true),
        'key_prefix' => env('CART_REDIS_PREFIX', 'cart:'),
        'ttl_guest_seconds' => env('CART_GUEST_TTL', 60 * 60 * 24 * 7), // 7 days
    ],
];
