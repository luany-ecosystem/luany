<?php

// Email — mailer()->to($email)->subject('…')->send('emails.welcome', [...]).
// Emails are LTE views in views/emails/.
return [
    // log (default: emails go to storage/logs — nothing to configure),
    // smtp, or array (tests).
    'default' => env('MAIL_MAILER', 'log'),

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name'    => env('MAIL_FROM_NAME', env('APP_NAME', 'Luany')),
    ],

    'mailers' => [
        'smtp' => [
            'host'       => env('MAIL_HOST', ''),
            'port'       => (int) env('MAIL_PORT', 587),
            'username'   => env('MAIL_USERNAME', ''),
            'password'   => env('MAIL_PASSWORD', ''),
            // tls (STARTTLS, required), ssl (port 465) or none (local relays only)
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        ],
    ],
];
