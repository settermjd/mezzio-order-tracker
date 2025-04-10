<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\AddNewParcel;

use App\Entity\Parcel;
use Laminas\EventManager\Event;
use Psr\Log\LoggerInterface;

use function assert;

readonly class NewParcelLoggerListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(Event $event): void
    {
        $params = $event->getParams();
        $parcel = $params['parcel'];

        assert($parcel instanceof Parcel);

        $this->logger->info('New parcel added', [
            'id'              => $parcel->getId(),
            'tracking_number' => $parcel->getParcelTrackingDetails()->getTrackingNumber(),
        ]);
    }
}
