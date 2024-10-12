<?php

namespace App\Notifications;

use App\Contracts\NotificationInterface;
use Illuminate\Notifications\Messages\VonageMessage;

class VonageNotification implements NotificationInterface
{
    public function send($notifiable, $message)
    {
       // Assuming $notifiable has a 'mobile' field that stores the user's mobile number
       $mobileNumber = $notifiable->mobile;

       return (new VonageMessage)
           ->content($message)
           ->from(config('services.vonage.sms_from'))
           ->to($mobileNumber);  // Send the SMS to the user's mobile number
    }
}
