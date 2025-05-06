<?php

declare(strict_types=1);

namespace App;

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
use Twilio\Rest\Client as TwilioRestClient;

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
     *
     * @return array<string,array<string,array<string,string|callable>>>
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
     *
     * @return array<string,array<string,string|callable>>
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                /**
                 * Register the handlers
                 */
                Handler\AddParcelHandler::class          => ReflectionBasedAbstractFactory::class,
                Handler\HomePageHandler::class           => ReflectionBasedAbstractFactory::class,
                Handler\UpdateParcelStatusHandler::class => ReflectionBasedAbstractFactory::class,
                Handler\ViewParcelDetailsHandler::class  => Handler\ParcelTrackerResultsHandlerFactory::class,

                /**
                 * Register the listeners
                 */
                LoggerListener::class => ReflectionBasedAbstractFactory::class,

                /**
                 * Register the services
                 */
                Service\ParcelService::class               => new class {
                    public function __invoke(ContainerInterface $container): Service\ParcelService
                    {
                        $entityManager = $container->get(EntityManager::class);
                        assert($entityManager instanceof EntityManagerInterface);

                        return new Service\ParcelService($entityManager);
                    }
                },
                TwilioRestClient::class                    => new class {
                    public function __invoke(ContainerInterface $container): TwilioRestClient
                    {
                        $config = $container->get('config')['twilio'] ?? [];

                        return new TwilioRestClient(
                            username: $config['account_sid'],
                            password: $config['auth_token'],
                        );
                    }
                },
                LoggerInterface::class                     => new class {
                    public function __invoke(ContainerInterface $container): LoggerInterface
                    {
                        $config = $container->get('config')['logger'] ?? [];

                        return new Logger($config['name'])
                            ->pushHandler(new StreamHandler(
                                __DIR__ . $config['location'],
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

                        $twilioClient = $container->get(TwilioRestClient::class);
                        assert($twilioClient instanceof TwilioRestClient);

                        $twilioConfig = $container->get('config')['twilio'];
                        assert(is_array($twilioConfig));

                        $eventManager->attach(
                            eventName: AddParcelHandler::EVENT_NAME,
                            listener: new NewParcelLoggerListener($logger),
                            priority: 100,
                        );

                        $eventManager->attach(
                            eventName: AddParcelHandler::EVENT_NAME,
                            listener: new NewParcelCreatedSMSNotificationListener(
                                $twilioClient,
                                $twilioConfig,
                                $logger,
                            ),
                            priority: 70,
                        );

                        $eventManager->attach(
                            eventName: AddParcelHandler::EVENT_NAME,
                            listener: new NewParcelCreatedWhatsAppNotificationListener(
                                $twilioClient,
                                $twilioConfig,
                                $logger,
                            ),
                            priority: 70,
                        );

                        $sendGridConfig = $container->get('config')['sendgrid'];
                        assert(is_array($sendGridConfig));

                        $eventManager->attach(
                            eventName: AddParcelHandler::EVENT_NAME,
                            listener: new NewParcelCreatedEmailNotificationListener(
                                new SendGrid($sendGridConfig['api_key']),
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
     *
     * @return array<string,array<string,array<int,string>>>
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
