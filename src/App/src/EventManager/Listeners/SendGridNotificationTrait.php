<?php

declare(strict_types=1);

namespace App\EventManager\Listeners;

use App\Entity\Parcel;
use Exception;
use SendGrid\Mail\TypeException;

use function sprintf;

trait SendGridNotificationTrait
{
    /**
     * @throws TypeException
     */
    public function sendNotification(Parcel $parcel): void
    {
        $customer = $parcel->getCustomer();
        if ($customer->getEmailAddress() === '') {
            return;
        }

        $this->email->setFrom(
            $this->config['sender']['email'],
            $this->config['sender']['name'],
        );
        $this->email->setSubject($this->messages['subject']);
        $this->email->addTo($customer->getEmailAddress(), $customer->fullName);
        $this->email->addContent(
            'text/html',
            sprintf(
                $this->messages['email_template'],
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
            $this->logger->info($this->messages['notification_sent'], [
                'status'  => $response->statusCode(),
                'headers' => $response->headers(),
            ]);
        } catch (Exception $e) {
            $this->logger->error($this->messages['notification_failed'], [$e->getMessage()]);
        }
    }
}
