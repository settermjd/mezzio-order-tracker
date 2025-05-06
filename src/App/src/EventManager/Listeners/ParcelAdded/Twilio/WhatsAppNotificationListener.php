<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\ParcelAdded\Twilio;

use App\Entity\Parcel;
use App\EventManager\Listeners\AbstractTwilioNotificationListener;
use App\EventManager\Listeners\TwilioNotificationType;
use App\EventManager\Listeners\TwilioNotificationTrait;

use function sprintf;

class WhatsAppNotificationListener extends AbstractTwilioNotificationListener
{
    use TwilioNotificationTrait;

    public const TwilioNotificationType  NOTIFICATION_TYPE = TwilioNotificationType::WHATSAPP;

    public function getNotificationMessage(Parcel $parcel): string
    {
        return sprintf(
            $this->messages['message'],
            $parcel->getDescription(),
            $parcel->getSupplier(),
            $parcel->getDeliveryService(),
            $parcel
                ->getParcelTrackingDetails()
                ->getTrackingNumber(),
        );
    }

    protected function init(): void
    {
        $message = <<<EOF
Your parcel (%s) %s, being sent by %s. 

Your tracking number is: %s. 

We'll notify you again as the parcel progresses further to you.
EOF;

        $cannotSendNotification = <<<EOF
Cannot send a new parcel WhatsApp notification. 
A Twilio WhatsApp number has not been set.
EOF;

        $failedToSendNotification = <<<EOF
Failed to send a new parcel WhatsApp notification.
EOF;

        $this->messages = [
            'cannot_sent_notification' => $cannotSendNotification,
            'message'                  => $message,
            'no_customer_phone_number' => 'The customer does not have a WhatsApp number.',
            'no_twilio_phone_number'   => 'A Twilio WhatsApp number has not been set.',
            'notification_failed'      => $failedToSendNotification,
            'notification_sent'        => 'New parcel update WhatsApp notification sent',
        ];
    }
}
