<?php

declare(strict_types=1);

namespace App\EventManager\Listeners;

use App\Entity\Parcel;
use App\Handler\AddParcelHandler;
use App\Handler\UpdateParcelStatusHandler;
use Laminas\EventManager\Event;
use Psr\Log\LoggerInterface;

readonly class LoggerListener
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @template TTarget of object|null
     * @template TParams of array{parcel: Parcel}
     * @param Event<TTarget, TParams> $event
     */
    public function __invoke(Event $event): void
    {
        $params = $event->getParams();
        $parcel = $params['parcel'];

        $this->logger->info($this->getLogMessage($event), [
            'id'              => $parcel->getId(),
            'tracking_number' => $parcel->getParcelTrackingDetails()->getTrackingNumber(),
        ]);
    }

    /**
     * @template TTarget of object|null
     * @template TParams of array{parcel: Parcel}
     * @param Event<TTarget, TParams> $event
     */
    public function getLogMessage(Event $event): string
    {
        return match ($event->getName()) {
            AddParcelHandler::EVENT_NAME => 'New parcel added',
            UpdateParcelStatusHandler::EVENT_NAME => 'Parcel status updated',
            default => "Unknown parcel event",
        };
    }
}
