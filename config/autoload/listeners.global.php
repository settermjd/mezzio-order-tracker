<?php

declare(strict_types=1);

use App\EventManager\Listeners;
use App\Handler;

return [
    "listeners" => [
        Handler\AddParcelHandler::EVENT_NAME => [
            [
                'listener' => Listeners\LoggerListener::class,
                'priority' => 10,
            ],
            [
                'listener'    => Listeners\ParcelAdded\Twilio\SMSNotificationListener::class,
                'priority' => 70,
            ],
            [
                'listener'    => Listeners\ParcelAdded\Twilio\WhatsAppNotificationListener::class,
                'priority' => 70,
            ],
            [
                'listener'    => Listeners\ParcelAdded\SendGrid\EmailNotificationListener::class,
                'priority' => 70,
            ]
        ],
        Handler\UpdateParcelStatusHandler::EVENT_NAME => [
            [
                'listener' => Listeners\LoggerListener::class,
            ],
            [
                'listener'    => Listeners\ParcelStatusUpdate\Twilio\SMSNotificationListener::class,
                'priority' => 70,
            ],
            [
                'listener' => Listeners\ParcelStatusUpdate\Twilio\WhatsAppNotificationListener::class,
                'priority' => 70,
            ],
            [
                'listener' => Listeners\ParcelStatusUpdate\SendGrid\EmailNotificationListener::class,
                'priority' => 70,
            ],
        ],
    ]
];
