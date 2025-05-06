<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\ParcelStatusUpdate\Twilio;

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
        $statusUpdates = $parcel->getParcelTrackingDetails()->getParcelStatusUpdates();
        return sprintf(
            $this->messages['message'],
            $parcel->getDescription(),
            $parcel
                ->getParcelTrackingDetails()
                ->getTrackingNumber(),
            $statusUpdates->last()->getDescription(),
        );
    }

    protected function init(): void
    {
        $message = <<<EOF
Your parcel (%s), tracking number %s is moving closer to you.
%s 
We'll email you again soon with the next status update.
EOF;

        $cannotSendNotification = <<<EOF
Cannot send a new parcel WhatsApp notification. 
A Twilio WhatsApp number has not been set.
EOF;

        $failedToSendNotification = <<<EOF
Cannot send a new parcel WhatsApp notification. 
The customer does not have a WhatsApp number.
EOF;

        $this->messages = [
            'cannot_sent_notification' => $cannotSendNotification,
            'message'                  => $message,
            'no_customer_phone_number' => 'The customer does not have a WhatsApp number.',
            'no_twilio_phone_number'   => 'A Twilio WhatsApp number has not been set.',
            'notification_failed'      => $failedToSendNotification,
            'notification_sent'        => 'Parcel status update WhatsApp notification sent',
        ];
    }
}
