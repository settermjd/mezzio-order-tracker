<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\AddNewParcel;

use App\Entity\Parcel;
use Twilio\Exceptions\TwilioException;

use function array_key_exists;
use function sprintf;

readonly class NewParcelCreatedSMSNotificationListener extends NewParcelCreatedTwilioNotificationListener
{
    public const string MESSAGE_TEMPLATE = <<<EOF
Your parcel (%s) from %s is being tracked with tracking number %s. 
It is being sent by %s. We'll notify you again as the parcel progresses further to you.
EOF;

    public function sendNotification(Parcel $parcel): void
    {
        if (
            ! array_key_exists('phone_number', $this->twilioConfig)
            || $this->twilioConfig['phone_number'] === ''
        ) {
            $this->logger->debug(
                'Cannot send a new parcel SMS notification. A Twilio phone number has not been set.',
                [
                    'id' => $parcel->getId(),
                ]
            );
            return;
        }

        $phoneNumber = $parcel->getCustomer()->getPhoneNumber();
        if ($phoneNumber === '') {
            $this->logger->debug(
                'Cannot send a new parcel SMS notification. The customer does not have a phone number.',
                [
                    'id' => $parcel->getId(),
                ]
            );
            return;
        }

        try {
            $this->twilioClient
                ->messages
                ->create(
                    $phoneNumber,
                    [
                        "body" => sprintf(
                            self::MESSAGE_TEMPLATE,
                            $parcel->getDescription(),
                            $parcel->getSupplier(),
                            $parcel
                                ->getParcelTrackingDetails()
                                ->getTrackingNumber(),
                            $parcel->getDeliveryService(),
                        ),
                        "from" => $this->twilioConfig['phone_number'],
                    ]
                );
        } catch (TwilioException $e) {
            $this->logger->error(
                'Failed to send a new parcel SMS notification',
                [
                    'error' => $e->getMessage(),
                    'from'  => $this->twilioConfig['whatsapp_number'],
                    'id'    => $parcel->getId(),
                    'to'    => $phoneNumber,
                ]
            );
            return;
        }

        $this->logger->info('New parcel SMS notification sent', [
            'id'              => $parcel->getId(),
            'recipient'       => $phoneNumber,
            'sender'          => $this->twilioConfig['phone_number'],
            'tracking_number' => $parcel->getParcelTrackingDetails()->getTrackingNumber(),
        ]);
    }
}
