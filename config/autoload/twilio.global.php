<?php

declare(strict_types=1);

return [
    'twilio' => [
        'account_sid'     => $_ENV['TWILIO_ACCOUNT_SID'],
        'auth_token'      => $_ENV['TWILIO_AUTH_TOKEN'],
        'phone_number'    => $_ENV['TWILIO_PHONE_NUMBER'],
        'whatsapp_number' => $_ENV['TWILIO_WHATSAPP_NUMBER'],
    ],
];
