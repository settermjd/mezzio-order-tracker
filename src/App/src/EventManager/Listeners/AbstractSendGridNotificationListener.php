<?php

declare(strict_types=1);

namespace App\EventManager\Listeners;

use App\Entity\Parcel;
use Laminas\EventManager\Event;
use Psr\Log\LoggerInterface;
use SendGrid;
use SendGrid\Mail\Mail;

abstract class AbstractSendGridNotificationListener implements NotificationListenerInterface
{
    use SendGridNotificationTrait;

    /** @var array<string,string> */
    protected array $messages;

    /**
     * @param array<string,string|array<string,string>> $config
     */
    public function __construct(
        protected SendGrid $sendgrid,
        protected Mail $email,
        protected LoggerInterface $logger,
        protected array $config,
    ) {
        $this->init();
    }

    abstract protected function init(): void;

    /**
     * @template TTarget of object|null
     * @template TParams of array{parcel: Parcel}
     * @param Event<TTarget, TParams> $event
     * @throws SendGrid\Mail\TypeException
     */
    public function __invoke(Event $event): void
    {
        $params = $event->getParams();
        $parcel = $params['parcel'];

        $this->sendNotification($parcel);
    }
}
