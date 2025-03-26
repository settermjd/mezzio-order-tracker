<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Parcel;
use App\Entity\ParcelTrackingDetails;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;

readonly class ParcelTrackerResultsHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $repository = $this->entityManager->getRepository(Parcel::class);
        $parcelTrackingDetails = new ParcelTrackingDetails();
        $parcelTrackingDetails->trackingId = $request->getParsedBody()['tracking_number'];

        $parcel = $repository->findOneBy(
            [
                'parcelTrackingDetails' => $parcelTrackingDetails,
            ]
        );
        var_dump($parcel);
        exit();
        return new HtmlResponse($this->renderer->render(
            'app::parcel-tracker-results',
            []
        ));
    }
}
