<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\ParcelStatusUpdate\Twilio;

use App\Entity\Parcel;
use App\EventManager\Listeners\AbstractTwilioNotificationListener;
use App\EventManager\Listeners\TwilioNotificationType;
use App\EventManager\Listeners\TwilioNotificationTrait;

use function sprintf;

class SMSNotificationListener extends AbstractTwilioNotificationListener
{
    use TwilioNotificationTrait;

    public const TwilioNotificationType  NOTIFICATION_TYPE = TwilioNotificationType::SMS;

    public function getNotificationMessage(Parcel $parcel): string
    {
        $parcelStatusUpdates = $parcel->getParcelTrackingDetails()->getParcelStatusUpdates();

        return sprintf(
            $this->messages['message'],
            $parcel->getDescription(),
            $parcel
                ->getParcelTrackingDetails()
                ->getTrackingNumber(),
            $parcelStatusUpdates->last()->getDescription(),
        );
    }

    protected function init(): void
    {
        $message        = <<<EOF
Your parcel (%s) with tracking number %s is moving closer to you. 
%s 
We'll email you again soon with the next status update.
EOF;
        $this->messages = [
            'cannot_sent_notification' => 'Cannot send parcel status update SMS notification.',
            'message'                  => $message,
            'no_customer_phone_number' => 'The customer does not have a phone number.',
            'no_twilio_phone_number'   => 'A Twilio phone number has not been set.',
            'notification_failed'      => 'Failed to send parcel status update SMS notification',
            'notification_sent'        => 'Parcel status update SMS notification sent',
        ];
    }
}
