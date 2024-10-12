<?php
namespace App\Listeners;

use App\Events\UserRegistered;
use App\Notifications\EmailNotification;
use App\Notifications\VonageNotification;

class SendRegistrationNotifications
{
    public function handle(UserRegistered $event)
    {
        // Send notifications to the user in the background
        
        $user = $event->user;
        $messages = ['message' => 'Registration successful! Check your status.'];
        $user->notify(new EmailNotification($user, null, null, $messages));
        //$user->notify(new VonageNotification($user));
    }
}
