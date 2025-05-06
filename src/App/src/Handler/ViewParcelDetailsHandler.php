<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Parcel;
use App\Repository\ParcelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class ViewParcelDetailsHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $renderer,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $postData = (array) $request->getParsedBody();

        /** @var ParcelRepository $repository */
        $repository = $this->entityManager->getRepository(Parcel::class);
        $parcel     = $repository->findByTrackingNumber($postData['tracking_number']);
        if (! $parcel instanceof Parcel) {
            return new RedirectResponse('/404');
        }

        return new HtmlResponse($this->renderer->render(
            'app::view-parcel-details',
            [
                "parcel" => $parcel,
            ]
        ));
    }
}
