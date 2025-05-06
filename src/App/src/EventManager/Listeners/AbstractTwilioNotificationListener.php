<?php

declare(strict_types=1);

namespace App\EventManager\Listeners;

use App\Entity\Parcel;
use Laminas\EventManager\Event;
use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;

abstract class AbstractTwilioNotificationListener implements NotificationListenerInterface
{
    /**
     * @param array<string,string> $twilioConfig
     */
    public function __construct(
        protected Client $twilioClient,
        protected array $twilioConfig,
        protected LoggerInterface $logger,
    ) {
        $this->init();
    }

    abstract protected function init(): void;

    /**
     * @template TTarget of object|null
     * @template TParams of array{parcel: Parcel}
     * @param Event<TTarget, TParams> $event
     */
    public function __invoke(Event $event): void
    {
        $params = $event->getParams();
        $parcel = $params['parcel'];

        $this->sendNotification($parcel);
    }
}
