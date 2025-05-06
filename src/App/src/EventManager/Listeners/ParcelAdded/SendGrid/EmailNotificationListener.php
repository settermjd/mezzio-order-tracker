<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\ParcelAdded\SendGrid;

use App\Entity\Parcel;
use App\EventManager\Listeners\AbstractSendGridNotificationListener;
use App\EventManager\Listeners\SendGridNotificationTrait;

use function sprintf;

class EmailNotificationListener extends AbstractSendGridNotificationListener
{
    use SendGridNotificationTrait;

    protected function init(): void
    {
        $emailTemplate  = <<<EOF
<p>Your parcel (%s) from %s is being tracked with tracking number <strong>%s</strong>.</p>
<p>It is being sent by %s.</p>
<p>We'll email you again as the parcel progresses further to you.</p>
EOF;
        $this->messages = [
            'email_template'      => $emailTemplate,
            'notification_failed' => 'New parcel email notification failed',
            'notification_sent'   => 'New parcel email notification sent',
            'subject'             => 'Your parcel is being tracked.',
        ];
    }

    public function getNotificationMessage(Parcel $parcel): string
    {
        return sprintf(
            $this->messages['email_template'],
            $parcel->getDescription(),
            $parcel->getSupplier(),
            $parcel
                ->getParcelTrackingDetails()
                ->getTrackingNumber(),
            $parcel->getDeliveryService(),
        );
    }
}
