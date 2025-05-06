<?php

declare(strict_types=1);

namespace AppTest\Handler;

use App\Entity\Parcel;
use App\Handler\AddParcelHandler;
use App\Service\ParcelService;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\EventManager\EventManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

use function json_encode;

class AddParcelHandlerTest extends TestCase
{
    public function testCanAddNewParcels(): void
    {
        $parcelDetails = [
            'customer'        => '1',
            'deliveryService' => 'Standard delivery',
            'description'     => 'SYMFONISK WiFi bookshelf speaker, black smart/gen 2',
            'dimensions'      => '10 (w) x 15 (d) x 31 (h). Cord length: 150cm.',
            'supplier'        => 'SONOS (via IKEA)',
            'weight'          => '2480',
        ];

        $parcel = $this->createMock(Parcel::class);

        $parcelService = $this->createMock(ParcelService::class);
        $parcelService
            ->expects($this->once())
            ->method('addParcel')
            ->with($parcelDetails)
            ->willReturn($parcel);

        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager
            ->expects($this->once())
            ->method('trigger')
            ->with('newParcel', null, ['parcel' => $parcel]);

        $handler = new AddParcelHandler(
            $parcelService,
            $eventManager
        );

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($parcelDetails);

        $response = $handler->handle($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(StatusCodeInterface::STATUS_ACCEPTED, $response->getStatusCode());
        $this->assertEquals(
            json_encode(
                [
                    'status'  => 'success',
                    'message' => 'New parcel request received',
                    'details' => $parcelDetails,
                ],
                JsonResponse::DEFAULT_JSON_FLAGS
            ),
            (string) $response->getBody()
        );
    }
}
