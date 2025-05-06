<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\ParcelService;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\EventManager\EventManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

readonly class UpdateParcelStatusHandler implements RequestHandlerInterface
{
    public const string EVENT_NAME = 'parcelStatusUpdate';

    public function __construct(
        private ParcelService $parcelService,
        private EventManagerInterface $eventManager,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * This endpoint receives a POST request with the parcel tracking number and status update
     * data. With that information, it then records a status update against the parcel with
     * that tracking number.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $requestData = (array) $request->getParsedBody();

        $this->logger->info("Parcel update request", $requestData);

        $parcelTrackingNumber = $requestData["tracking_number"];
        unset($requestData["tracking_number"]);

        $parcel = $this->parcelService->addStatusUpdate($parcelTrackingNumber, $requestData);

        $this->eventManager->trigger(eventName: self::EVENT_NAME, argv: ['parcel' => $parcel]);

        return new JsonResponse(
            [
                'status'  => 'success',
                'message' => 'New parcel status update request received',
                'details' => $requestData,
            ],
            StatusCodeInterface::STATUS_ACCEPTED
        );
    }
}
