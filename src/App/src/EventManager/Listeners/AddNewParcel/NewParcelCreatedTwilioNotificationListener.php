<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\AddNewParcel;

use App\Entity\Parcel;
use Laminas\EventManager\Event;
use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;

use function assert;

abstract readonly class NewParcelCreatedTwilioNotificationListener
{
    public function __construct(
        protected Client $twilioClient,
        protected array $twilioConfig,
        protected LoggerInterface $logger
    ) {
    }

    public function __invoke(Event $event): void
    {
        $params = $event->getParams();
        $parcel = $params['parcel'];
        assert($parcel instanceof Parcel);

        $this->sendNotification($parcel);
    }

    /**
     * Implementations of this function send notifications using Twilio's Programmable Messaging API
     *
     * It's expected, at this point, that there will only be two implementations; one for SMS and one for WhatsApp .
     *
     * @param Parcel $parcel The newly created parcel
     */
    abstract public function sendNotification(Parcel $parcel): void;
}
