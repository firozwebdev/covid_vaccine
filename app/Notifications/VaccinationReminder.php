<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Contracts\NotificationInterface;

class VaccinationReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $scheduledDate;
    protected $notificationDate;
    protected $notifications;

    public function __construct($user, $scheduledDate, $notificationDate, array $notifications)
    {
        $this->user = $user;
        $this->scheduledDate = $scheduledDate;
        $this->notificationDate = $notificationDate;
        $this->notifications = $notifications; // Array of notification channels
    }

    public function via($notifiable)
    {
        // Return only the necessary channels (can be dynamically determined)
        return ['mail', 'vonage'];
    }

    public function toMail($notifiable)
    {
        return $this->sendNotification($notifiable, 'email');
    }

    public function toVonage($notifiable)
    {
        return $this->sendNotification($notifiable, 'sms');
    }

    protected function sendNotification($notifiable, $type)
    {
        $message = 'Hello ' . $this->user->name . 
                   ', your vaccination is scheduled for ' . 
                   $this->scheduledDate->format('l, F j, Y \a\t g:i A') . '.';

        if (isset($this->notifications[$type])) {
            return $this->notifications[$type]->send($notifiable, $message);
        }

        return null;
    }
}
