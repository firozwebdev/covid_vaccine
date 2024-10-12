<?php
namespace App\Notifications;

use App\Contracts\NotificationInterface;
use Illuminate\Notifications\Messages\VonageMessage;

class VonageNotification implements NotificationInterface
{
    public function send($notifiable, $message)
    {
        

        return (new VonageMessage)
            ->content($message)
            ->from(config('services.vonage.sms_from'));  // Send the SMS from your configured number
    }
}
