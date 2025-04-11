<?php

declare(strict_types=1);

return [
    'twilio' => [
        'account_sid' => $_ENV['TWILIO_ACCOUNT_SID'],
        'auth_token'  => $_ENV['TWILIO_AUTH_TOKEN'],
        'sender'      => $_ENV['TWILIO_PHONE_NUMBER'],
    ],
];
