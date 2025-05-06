<?php

declare(strict_types=1);

namespace App;

use App\EventManager\Listeners;
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

                EventManagerInterface::class => ReflectionBasedAbstractFactory::class,

                LoggerInterface::class                                => new class {
                    public function __invoke(ContainerInterface $container): LoggerInterface
                    {
                        $config = $container->get('config')['logger'] ?? [];

                        return new Logger($config['name'])
                            ->pushHandler(new StreamHandler(
                                __DIR__ . $config['location'],
                                Level::Debug,
                            ));
                    }
                },

                Service\ParcelService::class                          => new class {
                    public function __invoke(ContainerInterface $container): Service\ParcelService
                    {
                        $entityManager = $container->get(EntityManager::class);
                        assert($entityManager instanceof EntityManagerInterface);

                        return new Service\ParcelService($entityManager);
                    }
                },
                
                TwilioRestClient::class                               => new class {
                    public function __invoke(ContainerInterface $container): TwilioRestClient
                    {
                        $config = $container->get('config')['twilio'] ?? [];

                        return new TwilioRestClient(
                            username: $config['account_sid'],
                            password: $config['auth_token'],
                        );
                    }
                },

                Listeners\LoggerListener::class => ReflectionBasedAbstractFactory::class,

                Listeners\ParcelAdded\Twilio\SMSNotificationListener::class               => new class {
                    public function __invoke(ContainerInterface $container): Listeners\ParcelAdded\Twilio\SMSNotificationListener
                    {
                        return new Listeners\ParcelAdded\Twilio\SMSNotificationListener(
                            $container->get(TwilioRestClient::class),
                            $container->get('config')['twilio'],
                            $container->get(Listeners\LoggerListener::class),
                        );
                    }
                },
                Listeners\ParcelAdded\Twilio\WhatsAppNotificationListener::class          => new class {
                    public function __invoke(ContainerInterface $container): Listeners\ParcelAdded\Twilio\WhatsAppNotificationListener
                    {
                        return new Listeners\ParcelAdded\Twilio\WhatsAppNotificationListener(
                            $container->get(TwilioRestClient::class),
                            $container->get('config')['twilio'],
                            $container->get(Listeners\LoggerListener::class),
                        );
                    }
                },
                Listeners\ParcelStatusUpdate\Twilio\SMSNotificationListener::class      => new class {
                    public function __invoke(ContainerInterface $container): Listeners\ParcelStatusUpdate\Twilio\SMSNotificationListener
                    {
                        return new Listeners\ParcelStatusUpdate\Twilio\SMSNotificationListener(
                            $container->get(TwilioRestClient::class),
                            $container->get('config')['twilio'],
                            $container->get(Listeners\LoggerListener::class),
                        );
                    }
                },
                Listeners\ParcelStatusUpdate\Twilio\WhatsAppNotificationListener::class => new class {
                    public function __invoke(ContainerInterface $container): Listeners\ParcelStatusUpdate\Twilio\WhatsAppNotificationListener
                    {
                        return new Listeners\ParcelStatusUpdate\Twilio\WhatsAppNotificationListener(
                            $container->get(TwilioRestClient::class),
                            $container->get('config')['twilio'],
                            $container->get(Listeners\LoggerListener::class),
                        );
                    }
                },
                Listeners\ParcelAdded\SendGrid\EmailNotificationListener::class           => new class {
                    public function __invoke(ContainerInterface $container): Listeners\ParcelAdded\SendGrid\EmailNotificationListener
                    {
                        $sendGridConfig = $container->get('config')['sendgrid'];
                        assert(is_array($sendGridConfig));

                        return new Listeners\ParcelAdded\SendGrid\EmailNotificationListener(
                            new SendGrid($sendGridConfig['api_key']),
                            new Mail(),
                            $container->get(Listeners\LoggerListener::class),
                            $container->get('config')['sendgrid'],
                        );
                    }
                },
                Listeners\ParcelStatusUpdate\SendGrid\EmailNotificationListener::class    => new class {
                    public function __invoke(ContainerInterface $container): Listeners\ParcelStatusUpdate\SendGrid\EmailNotificationListener
                    {
                        $sendGridConfig = $container->get('config')['sendgrid'];
                        assert(is_array($sendGridConfig));

                        return new Listeners\ParcelStatusUpdate\SendGrid\EmailNotificationListener(
                            new SendGrid($sendGridConfig['api_key']),
                            new Mail(),
                            $container->get(Listeners\LoggerListener::class),
                            $container->get('config')['sendgrid'],
                        );
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
