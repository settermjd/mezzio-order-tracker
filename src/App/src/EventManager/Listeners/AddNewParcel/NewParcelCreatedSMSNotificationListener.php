<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\AddNewParcel;

use App\Entity\Parcel;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\EventManager\Event;
use Twilio\Rest\Client;

use function array_key_exists;
use function assert;
use function sprintf;

readonly class NewParcelCreatedSMSNotificationListener
{
    public const string SMS_MESSAGE_TEMPLATE = <<<EOF
Your parcel (%s) from %s is being tracked with tracking number %s. 
It is being sent by %s. We'll notify you again as the parcel progresses further to you.
EOF;

    public function __construct(
        private Client $twilioClient,
        private array $twilioConfig,
    ) {
    }

    public function __invoke(Event $event): void
    {
        if (
            ! array_key_exists('sender', $this->twilioConfig)
            || $this->twilioConfig['sender'] === ''
        ) {
            return;
        }

        $params = $event->getParams();
        $parcel = $params['parcel'];

        assert($parcel instanceof Parcel);

        $phoneNumber = $parcel->getCustomer()->getPhoneNumber();
        if ($phoneNumber === '') {
            return;
        }

        $this->twilioClient
            ->messages
            ->create(
                $phoneNumber,
                [
                    "body" => sprintf(
                        self::SMS_MESSAGE_TEMPLATE,
                        $parcel->getDescription(),
                        $parcel->getSupplier(),
                        $parcel
                            ->getParcelTrackingDetails()
                            ->getTrackingNumber(),
                        $parcel->getDeliveryService(),
                    ),
                    "from" => $this->twilioConfig['sender'],
                ]
            );
    }
}
