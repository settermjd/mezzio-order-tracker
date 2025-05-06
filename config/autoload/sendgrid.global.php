<?php

declare(strict_types=1);

return [
    'sendgrid' => [
        'api_key' => $_ENV['SENDGRID_API_KEY'] ?? '',
        'sender'  => [
            'email' => $_ENV['SENDGRID_SENDER_EMAIL'] ?? '',
            'name'  => $_ENV['SENDGRID_SENDER_NAME'] ?? '',
        ],
    ],
];
