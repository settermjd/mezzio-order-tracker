<?php

declare(strict_types=1);

return [
    'sendgrid' => [
        'sender' => [
            'email' => $_ENV['SENDGRID_SENDER_EMAIL'],
            'name'  => $_ENV['SENDGRID_SENDER_NAME'],
        ],
    ],
];
