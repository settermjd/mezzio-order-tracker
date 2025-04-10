<?php

declare(strict_types=1);

namespace App;

use App\EventManager\Listeners\AddNewParcel\NewParcelCreatedEmailNotificationListener;
use App\EventManager\Listeners\AddNewParcel\NewParcelCreatedSMSNotificationListener;
use App\EventManager\Listeners\AddNewParcel\NewParcelLoggerListener;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\EventManager\EventManager;
use Laminas\EventManager\EventManagerInterface;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use SendGrid;
use SendGrid\Mail\Mail;
use Twilio\Rest\Client;

use function assert;
use function is_array;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handler\HomePageHandler::class             => Handler\HomePageHandlerFactory::class,
                Handler\ParcelTrackerResultsHandler::class => Handler\ParcelTrackerResultsHandlerFactory::class,
                Handler\AddParcelHandler::class            => ReflectionBasedAbstractFactory::class,
                Service\ParcelService::class               => new class {
                    public function __invoke(ContainerInterface $container): Service\ParcelService
                    {
                        $entityManager = $container->get(EntityManager::class);
                        assert($entityManager instanceof EntityManagerInterface);

                        return new Service\ParcelService($entityManager);
                    }
                },
                Client::class                              => new class {
                    public function __invoke(ContainerInterface $container): Client
                    {
                        return new Client(
                            username: $_ENV['TWILIO_ACCOUNT_SID'],
                            password: $_ENV['TWILIO_AUTH_TOKEN'],
                        );
                    }
                },
                LoggerInterface::class                     => new class {
                    public function __invoke(ContainerInterface $container): LoggerInterface
                    {
                        return new Logger('app.logger')
                            ->pushHandler(new StreamHandler(
                                __DIR__ . "/../../../data/log/app.log",
                                Level::Debug
                            ));
                    }
                },
                EventManagerInterface::class               => new class {
                    public function __invoke(ContainerInterface $container): EventManagerInterface
                    {
                        $eventManager = new EventManager();

                        $logger = $container->get(LoggerInterface::class);
                        assert($logger instanceof LoggerInterface);

                        $entityManager = $container->get(EntityManager::class);
                        assert($entityManager instanceof EntityManagerInterface);

                        $twilioClient = $container->get(Client::class);
                        assert($twilioClient instanceof Client);

                        $twilioConfig = $container->get('config')['twilio'];
                        assert(is_array($twilioConfig));

                        $sendGridConfig = $container->get('config')['sendgrid'];
                        assert(is_array($sendGridConfig));

                        $eventManager->attach(
                            eventName: 'newParcel',
                            listener: new NewParcelLoggerListener($logger),
                            priority: 100,
                        );

                        $eventManager->attach(
                            eventName: 'newParcel',
                            listener: new NewParcelCreatedSMSNotificationListener(
                                $twilioClient,
                                $twilioConfig
                            ),
                            priority: 70,
                        );

                        $eventManager->attach(
                            eventName: 'newParcel',
                            listener: new NewParcelCreatedEmailNotificationListener(
                                new SendGrid($_ENV['SENDGRID_API_KEY']),
                                new Mail(),
                                $logger,
                                $sendGridConfig
                            ),
                            priority: 70,
                        );

                        return $eventManager;
                    }
                },
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => [__DIR__ . '/../templates/app'],
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }
}
