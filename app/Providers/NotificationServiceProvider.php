<?php

namespace App\Providers;


use App\Notifications\SmsNotification;
use Illuminate\Support\ServiceProvider;
use App\Contracts\NotificationInterface;
use App\Notifications\EmailNotification;
use App\Notifications\VonageNotification;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind notification classes to their interface
        $this->app->bind(NotificationInterface::class, function ($app, $params) {
            // Expecting 'type' and any additional required parameters
            $user = $params['user'] ?? null;
            $scheduledDate = $params['scheduledDate'] ?? null;
            $notificationDate = $params['notificationDate'] ?? null;
            $messages = $params['messages'] ?? null;
            
            switch ($params['type']) {
                case 'email':
                    return new EmailNotification($user, $scheduledDate, $notificationDate, $messages);
                case 'sms':
                    //return new SmsNotification($user, $scheduledDate, $notificationDate, $messages);
                default:
                    throw new \InvalidArgumentException("Unsupported notification type: {$params['type']}");
            }
        });
    }
}
