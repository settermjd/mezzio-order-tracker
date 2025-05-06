<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\ParcelAdded\Twilio;

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
        return sprintf(
            $this->messages['message'],
            $parcel->getDescription(),
            $parcel->getSupplier(),
            $parcel
                ->getParcelTrackingDetails()
                ->getTrackingNumber(),
            $parcel->getDeliveryService(),
        );
    }

    protected function init(): void
    {
        $message = <<<EOF
Your parcel (%s) from %s is being tracked with tracking number %s. 

It is being sent by %s. 

We'll notify you again as the parcel progresses further to you.
EOF;

        $this->messages = [
            'cannot_sent_notification' => 'Cannot send new parcel SMS notification.',
            'message'                  => $message,
            'no_customer_phone_number' => 'The customer does not have a phone number.',
            'no_twilio_phone_number'   => 'A Twilio phone number has not been set.',
            'notification_failed'      => 'Failed to send new parcel SMS notification',
            'notification_sent'        => 'New parcel SMS notification sent',
        ];
    }
}
