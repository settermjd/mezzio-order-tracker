<?php

declare(strict_types=1);

namespace App\Handler;

use Doctrine\ORM\EntityManager;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

use function assert;

class ParcelTrackerResultsHandlerFactory
{
    public function __invoke(ContainerInterface $container): ViewParcelDetailsHandler
    {
        $renderer = $container->get(TemplateRendererInterface::class);
        assert($renderer instanceof TemplateRendererInterface);

        $entityManager = $container->get(EntityManager::class);
        assert($entityManager instanceof EntityManager);

        return new ViewParcelDetailsHandler($renderer, $entityManager);
    }
}
