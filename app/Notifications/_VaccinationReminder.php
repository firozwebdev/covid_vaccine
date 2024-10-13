<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Contracts\NotificationInterface;
use Illuminate\Support\Facades\App;

class VaccinationReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $scheduledDate;
    protected $notificationDate;

    public function __construct($user, $scheduledDate, $notificationDate)
    {
        $this->user = $user;
        $this->scheduledDate = $scheduledDate;
        $this->notificationDate = $notificationDate;
    }

    public function via($notifiable)
    {
        // Retrieve and validate the configured channels
        $notifications = config('notification.channels', []);
        \Log::debug('Notification channels: ', $notifications); // Log the channels
        if (empty($notifications)) {
            // Handle the case where no notification channels are defined
            return ['mail']; // Default to mail or handle accordingly
        }

        return $notifications;
    }

    public function toMail($notifiable)
    {
        return $this->createMessage('email', $notifiable);
    }

    public function toVonage($notifiable)
    {
        return $this->createMessage('sms', $notifiable);
    }

    protected function createMessage($type, $notifiable)
    {
        $messageContent = 'Hello ' . $this->user->name . 
                          ', your vaccination is scheduled for ' . 
                          $this->scheduledDate->format('l, F j, Y \a\t g:i A') . '.';

        // Resolve the notification class from the service container
        $notification = App::make(NotificationInterface::class, ['type' => $type]);

        // Send the notification using the corresponding method
        return $notification->send($notifiable, $messageContent);
    }

    public function toArray($notifiable)
    {
        return [
            'scheduled_date' => $this->scheduledDate,
        ];
    }
}
