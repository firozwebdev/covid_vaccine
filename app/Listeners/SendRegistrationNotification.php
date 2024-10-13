<?php
namespace App\Listeners;

use App\Events\UserRegistered;
use App\Notifications\SmsNotification;
use App\Contracts\NotificationInterface;
use App\Notifications\EmailNotification;


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
        //$user->notify(new SmsNotification($user, null, null, $messages));
        $notification = app(NotificationInterface::class, [
            'type' => 'email',
            'messages' => $messages,
        ]);
        
        $user->notify($notification);
        
    }
}
