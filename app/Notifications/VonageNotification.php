<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage;

class VonageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $scheduledDate;
    protected $notificationDate;
    protected $messages;

    public function __construct($user, $scheduledDate, $notificationDate, $messages)
    {
        $this->user = $user;
        $this->scheduledDate = $scheduledDate;
        $this->notificationDate = $notificationDate;
        $this->messages = $messages;
    }

    public function via($notifiable)
    {
        return ['vonage']; // Ensure it's using the SMS channel
    }

    public function toVonage($notifiable)
    {
        // For testing purpose
        \Log::info('Simulated Vonage SMS notification for user ID: ' . $this->user->id);
        \Log::info('Message content: Your vaccination is scheduled for ' . $this->scheduledDate . '. You will receive a reminder on ' . $this->notificationDate);
        // End for testing purpose
        return (new VonageMessage)
            ->content('Your vaccination is scheduled for ' . $this->scheduledDate . '. You will receive a reminder on ' . $this->notificationDate);
    }
}
