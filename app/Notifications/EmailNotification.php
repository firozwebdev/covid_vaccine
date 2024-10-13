<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $scheduledDate;
    protected $notificationDate;
    protected $messages = [];

    public function __construct($user, $scheduledDate, $notificationDate, $messages)
    {

        
        $this->user = $user;
        $this->scheduledDate = $scheduledDate;
        $this->notificationDate = $notificationDate;
        $this->messages = $messages;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable)
    // {
    //     return (new MailMessage)
    //         ->greeting('Hello ' . $this->user->name)
    //         ->line($this->messages['message']);

    // }
    public function toMail(object $notifiable)
    {
        $mailMessage = (new MailMessage)
            ->greeting($this->messages['greeting']);  // Add the greeting
    
        // Loop through each line in the 'message' array and add it to the email
        foreach ($this->messages['message'] as $line) {
            if (!empty($line)) {
                $mailMessage->line($line);
            }
        }
    
        return $mailMessage;
    }
    


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'scheduled_date' => $this->scheduledDate ?? null,
        ];
    }
}
