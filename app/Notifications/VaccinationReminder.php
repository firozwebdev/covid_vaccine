<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Vonage\SMS\Client as NexmoClient;

class VaccinationReminder extends Notification
{
    use Queueable;

    protected $user;
    protected $scheduledDate;
    protected $notificationDate;
    protected $nexmo;

    public function __construct($user, $scheduledDate, $notificationDate)
    {
        $this->user = $user;
        $this->scheduledDate = $scheduledDate;
        $this->notificationDate = $notificationDate;
        $this->nexmo = app(NexmoClient::class); // Initialize Nexmo client
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'nexmo']; // Send both email and SMS
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return (new MailMessage)
            ->greeting('Hello ' . $this->user->name)
            ->line('Your vaccination is scheduled for ' . $this->scheduledDate->format('l, F j, Y \a\t g:i A'))
            ->line('Thanks for your patience!');
    }

    /**
     * Send SMS notification via Nexmo.
     */
    public function toNexmo($notifiable)
    {
        $message = 'Hello ' . $this->user->name . 
                   ', your vaccination is scheduled for ' . 
                   $this->scheduledDate->format('l, F j, Y \a\t g:i A') . '.';

        $this->nexmo->send([
            'to' => $this->user->mobile,  // User's mobile number
            'from' => config('services.nexmo.sms_from'), // Sender's number
            'text' => $message,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'scheduled_date' => $this->scheduledDate,
        ];
    }
}
