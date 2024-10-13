<?php

namespace App\Providers;


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
            switch ($params['type']) {
                case 'email':
                    return new EmailNotification($params['user'], $params['scheduledDate'] = null, $params['notificationDate']= null, $params['messages'] = []);
                case 'sms':
                    return new VonageNotification($params['user'], $params['scheduledDate'] = null, $params['notificationDate'] = null, $params['messages']= []);
                default:
                    throw new \InvalidArgumentException("Unsupported notification type: {$params['type']}");
            }
        });
    }
}
