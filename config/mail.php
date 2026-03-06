<?php

return [
    'enabled'    => (bool) env('MAIL_ENABLED', false),
    'host'       => env('MAIL_HOST', 'smtp.gmail.com'),
    'port'       => (int) env('MAIL_PORT', 587),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username'   => env('MAIL_USERNAME', ''),
    'password'   => env('MAIL_PASSWORD', ''),
    'from'       => [
        'email' => env('MAIL_FROM_EMAIL', ''),
        'name'  => env('MAIL_FROM_NAME', env('APP_NAME', 'Luany')),
    ],
];