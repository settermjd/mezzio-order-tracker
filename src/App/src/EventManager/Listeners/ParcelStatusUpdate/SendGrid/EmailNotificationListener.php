<?php

declare(strict_types=1);

namespace App\EventManager\Listeners\ParcelStatusUpdate\SendGrid;

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
<p>Your parcel (%s) with tracking number %s is moving closer to you.</p> 
<p>%s</p>
<p>We'll email you again soon with the next status update.</p>
EOF;
        $this->messages = [
            'email_template'      => $emailTemplate,
            'notification_failed' => 'Parcel status update email notification failed',
            'notification_sent'   => 'Parcel status update email notification sent',
            'subject'             => 'Parcel status update.',
        ];
    }

    public function getNotificationMessage(Parcel $parcel): string
    {
        $parcelStatusUpdates = $parcel->getParcelTrackingDetails()->getParcelStatusUpdates();

        return sprintf(
            $this->messages['email_template'],
            $parcel->getDescription(),
            $parcel
                ->getParcelTrackingDetails()
                ->getTrackingNumber(),
            $parcelStatusUpdates->last()->getDescription(),
        );
    }
}
