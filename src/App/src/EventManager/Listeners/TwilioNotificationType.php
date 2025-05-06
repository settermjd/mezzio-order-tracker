<?php

declare(strict_types=1);

namespace App\EventManager\Listeners;

enum TwilioNotificationType: string
{
    case SMS      = 'sms';
    case WHATSAPP = 'whatsapp';
}
