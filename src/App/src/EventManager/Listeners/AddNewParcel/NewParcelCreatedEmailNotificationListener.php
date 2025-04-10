<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\AddNewParcel;

use App\Entity\Parcel;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Laminas\EventManager\Event;
use Psr\Log\LoggerInterface;
use SendGrid;
use SendGrid\Mail\Mail;

use function assert;
use function sprintf;

readonly class NewParcelCreatedEmailNotificationListener
{
    public const string EMAIL_MESSAGE_TEMPLATE = <<<EOF
<p>Your parcel (%s) from %s is being tracked with tracking number <strong>%s</strong>.</p>
<p>It is being sent by %s.</p>
<p>We'll email you again as the parcel progresses further to you.</p>
EOF;

    public function __construct(
        private SendGrid $sendgrid,
        private Mail $email,
        private LoggerInterface $logger,
        private array $config
    ) {
    }

    public function __invoke(Event $event): void
    {
        $params = $event->getParams();
        $parcel = $params['parcel'];

        assert($parcel instanceof Parcel);

        $customer = $parcel->getCustomer();
        if ($customer->getEmailAddress() === '') {
            return;
        }

        $this->email->setFrom(
            $this->config['sender']['email'],
            $this->config['sender']['name'],
        );
        $this->email->setSubject('Your parcel is being tracked.');
        $this->email->addTo($customer->getEmailAddress(), $customer->fullName);
        $this->email->addContent(
            'text/html',
            sprintf(
                self::EMAIL_MESSAGE_TEMPLATE,
                $parcel->getDescription(),
                $parcel->getSupplier(),
                $parcel
                    ->getParcelTrackingDetails()
                    ->getTrackingNumber(),
                $parcel->getDeliveryService(),
            )
        );

        try {
            $response = $this->sendgrid->send($this->email);
            $this->logger->info('Email notification sent', [
                'status'  => $response->statusCode(),
                'headers' => $response->headers(),
            ]);
        } catch (Exception $e) {
            $this->logger->error('Email notification failed', [$e->getMessage()]);
        }
    }
}
