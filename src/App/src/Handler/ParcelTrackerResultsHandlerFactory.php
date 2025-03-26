<?php

declare(strict_types=1);

namespace App\Handler;

use Doctrine\ORM\EntityManager;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ParcelTrackerResultsHandlerFactory
{
    public function __invoke(ContainerInterface $container) : ParcelTrackerResultsHandler
    {
        $renderer = $container->get(TemplateRendererInterface::class);
        $entityManager = $container->get(EntityManager::class);
        return new ParcelTrackerResultsHandler($renderer, $entityManager);
    }
}
