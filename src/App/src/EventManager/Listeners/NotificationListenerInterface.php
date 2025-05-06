<?php

declare(strict_types=1);

namespace App\EventManager\Listeners;

use App\Entity\Parcel;

interface NotificationListenerInterface
{
    /**
     * getNotificationMessage returns the notification message to send to the recipient
     */
    public function getNotificationMessage(Parcel $parcel): string;

    /**
     * Implementations of this function send notifications using Twilio's Programmable Messaging API
     *
     * It's expected, at this point, that there will only be two implementations; one for SMS and one for WhatsApp .
     */
    public function sendNotification(Parcel $parcel): void;
}
