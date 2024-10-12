<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;  // Use VonageMessage for SMS
use Illuminate\Notifications\Notification;

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
        return ['mail', 'vonage'];  // Use 'vonage' instead of 'nexmo'
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Hello ' . $this->user->name)
            ->line('Your vaccination is scheduled for ' . $this->scheduledDate->format('l, F j, Y \a\t g:i A'))
            ->line('Thanks for your patience!');
    }

    public function toVonage($notifiable)
    {
        $message = 'Hello ' . $this->user->name . 
                   ', your vaccination is scheduled for ' . 
                   $this->scheduledDate->format('l, F j, Y \a\t g:i A') . '.';

        return (new VonageMessage)
            ->content($message)
            ->from(config('services.vonage.sms_from'));  // Sender's number from the configuration
    }

    public function toArray($notifiable)
    {
        return [
            'scheduled_date' => $this->scheduledDate,
        ];
    }
}
