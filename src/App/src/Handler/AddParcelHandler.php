<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\ParcelService;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\EventManager\EventManagerInterface;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This class handles adding new parcels to be tracked to the application.
 *
 * It doesn't do anything itself, directly. Rather, it triggers an event where the
 * subscribers to that event do the hard work.
 */
readonly class AddParcelHandler implements RequestHandlerInterface
{
    public const string EVENT_NAME = 'newParcel';

    public function __construct(
        private ParcelService $parcelService,
        private EventManagerInterface $eventManager,
    ) {
    }

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $requestData = $request->getParsedBody() ?? [];
        $parcel      = $this->parcelService->addParcel($requestData);

        $this->eventManager->trigger(eventName: self::EVENT_NAME, argv: ['parcel' => $parcel]);

        return new JsonResponse(
            [
                'status'  => 'success',
                'message' => 'New parcel request received',
                'details' => $requestData,
            ],
            StatusCodeInterface::STATUS_ACCEPTED
        );
    }
}
