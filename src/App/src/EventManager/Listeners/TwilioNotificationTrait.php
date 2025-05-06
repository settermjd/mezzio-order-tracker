<?php

declare(strict_types=1);

namespace App\EventManager\Listeners;

use App\Entity\Parcel;
use Twilio\Exceptions\TwilioException;

use function array_key_exists;

trait TwilioNotificationTrait
{
    /** @var array<string,string> */
    protected array $messages;

    public function sendNotification(Parcel $parcel): void
    {
        if (! $this->hasTwilioNumber()) {
            $this->logger->debug(
                $this->messages['cannot_send_notification'],
                [
                    'reason' => $this->messages['no_twilio_phone_number'],
                    'id'     => $parcel->getId(),
                ]
            );
            return;
        }

        $recipientNumber = $parcel->getCustomer()?->getPhoneNumber();
        if ($recipientNumber === '') {
            $this->logger->debug(
                $this->messages['cannot_send_notification'],
                [
                    'reason' => $this->messages['no_customer_phone_number'],
                    'id'     => $parcel->getId(),
                ]
            );
            return;
        }

        /** @phpstan-ignore identical.alwaysTrue, identical.alwaysFalse */
        if (self::NOTIFICATION_TYPE === TwilioNotificationType::WHATSAPP) {
            $recipientNumber = "whatsapp:{$recipientNumber}";
        }

        $senderNumber = $this->getTwilioNumber();
        try {
            $this->twilioClient
                ->messages
                ->create(
                    $recipientNumber,
                    [
                        "body" => $this->getNotificationMessage($parcel),
                        "from" => $senderNumber,
                    ]
                );
        } catch (TwilioException $e) {
            $this->logger->error(
                $this->messages['notification_failed'],
                [
                    'error'  => $e->getMessage(),
                    'sender' => $senderNumber,
                    'id'     => $parcel->getId(),
                    'to'     => $recipientNumber,
                ]
            );
            return;
        }

        $this->logger->info($this->messages['notification_sent'], [
            'id'              => $parcel->getId(),
            'recipient'       => $recipientNumber,
            'sender'          => $senderNumber,
            'tracking_number' => $parcel->getParcelTrackingDetails()->getTrackingNumber(),
        ]);
    }

    public function hasTwilioNumber(): bool
    {
        $numberField = match (self::NOTIFICATION_TYPE) {
            TwilioNotificationType::SMS => 'phone_number', // @phpstan-ignore match.alwaysFalse, match.alwaysTrue
            TwilioNotificationType::WHATSAPP => 'whatsapp_number', // @phpstan-ignore match.alwaysFalse
        };

        $this->logger->debug('Notification Type', [
            self::NOTIFICATION_TYPE->value,
            $numberField,
            $this->twilioConfig,
            $this->twilioConfig[$numberField],
        ]);

        return array_key_exists($numberField, $this->twilioConfig) && $this->twilioConfig[$numberField] !== '';
    }

    public function getTwilioNumber(): string
    {
        /** @phpstan-ignore identical.alwaysFalse, identical.alwaysTrue */
        return self::NOTIFICATION_TYPE === TwilioNotificationType::SMS
            ? $this->twilioConfig['phone_number']
            : "whatsapp:{$this->twilioConfig['whatsapp_number']}";
    }
}
