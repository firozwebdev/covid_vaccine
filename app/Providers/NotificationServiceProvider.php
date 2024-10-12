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
            switch ($params['type']) {
                case 'email':
                    return new EmailNotification();
                case 'sms':
                    return new VonageNotification();
                default:
                    throw new \InvalidArgumentException("Unsupported notification type: {$params['type']}");
            }
        });
    }
}
