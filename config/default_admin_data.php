<?php

/**
 * Analytics
 */
return [
    'email' => env('DEFAULT_ADMIN_EMAIL', 'admin@admin.com'),
    'password' => env('DEFAULT_ADMIN_PASSWORD', env('APP_KEY', 'secret')),
    'name' => env('DEFAULT_ADMIN_NAME', ''),
    'position' => env('DEFAULT_ADMIN_POSITION', ''),
];
