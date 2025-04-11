<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\AddNewParcel;

use App\Entity\Parcel;
use Twilio\Exceptions\TwilioException;

use function array_key_exists;
use function sprintf;

readonly class NewParcelCreatedWhatsAppNotificationListener extends NewParcelCreatedTwilioNotificationListener
{
    public const string MESSAGE_TEMPLATE = <<<EOF
Your parcel (%s) from %s is being tracked with tracking number %s. 
It is being sent by %s. We'll notify you again as the parcel progresses further to you.
EOF;

    public function sendNotification(Parcel $parcel): void
    {
        if (
            ! array_key_exists('whatsapp_number', $this->twilioConfig)
            || $this->twilioConfig['whatsapp_number'] === ''
        ) {
            $this->logger->debug(
                'Cannot send a new parcel WhatsApp notification. A Twilio WhatsApp number has not been set.',
                [
                    'id' => $parcel->getId(),
                ]
            );
            return;
        }

        $number = $parcel->getCustomer()->getWhatsAppNumber();
        if ($number === '') {
            $this->logger->debug(
                'Cannot send a new parcel WhatsApp notification. The customer does not have a WhatsApp number.',
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
                    "whatsapp:{$number}",
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
                        "from" => "whatsapp:{$this->twilioConfig['whatsapp_number']}",
                    ]
                );
        } catch (TwilioException $e) {
            $this->logger->error(
                'Failed to send a new parcel WhatsApp notification',
                [
                    'error' => $e->getMessage(),
                    'from'  => "whatsapp:{$this->twilioConfig['whatsapp_number']}",
                    'id'    => $parcel->getId(),
                    'to'    => "whatsapp:{$number}",
                ]
            );
            return;
        }

        $this->logger->info('New parcel WhatsApp notification sent', [
            'id'              => $parcel->getId(),
            'recipient'       => "whatsapp:{$number}",
            'sender'          => "whatsapp:{$this->twilioConfig['whatsapp_number']}",
            'tracking_number' => $parcel->getParcelTrackingDetails()->getTrackingNumber(),
        ]);
    }
}
