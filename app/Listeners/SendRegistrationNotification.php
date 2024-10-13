<?php
namespace App\Listeners;

use App\Events\UserRegistered;
use App\Notifications\EmailNotification;
use App\Notifications\VonageNotification;

class SendRegistrationNotification
{
    public function handle(UserRegistered $event)
    {
        // Send notifications to the user in the background
        
        $user = $event->user;
        $messages = [
            'greeting' => 'Thanks ' . $user->name,
            'message' => [
                'Your registration is successful.',
                'Please keep checking your email for verification and schedule time.',
            ],
        ];
        //$user->notify(new EmailNotification($user, null, null, $messages));
        $user->notify(new VonageNotification($user, null, null, $messages));
        
    }
}
