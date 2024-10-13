<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Contracts\NotificationInterface;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SmsNotification extends Notification implements ShouldQueue, NotificationInterface
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

    public function send($notification){}

    public function via($notifiable)
    {
        return ['sms']; // Use the custom channel name
    }

    public function toSms($notifiable)
    {
        // Construct the message text dynamically
        $messageText = $this->buildMessageText();

        return [
            'messages' => [
                [
                    'destinations' => [
                        ['to' => $this->user->mobile], // Ensure the mobile field is correct
                    ],
                    'from' => config('services.infobip.from'), // Sender ID
                    'text' => $messageText, // Dynamic message text
                ],
            ],
        ];
    }

    protected function buildMessageText()
    {
        // Start with the greeting, make sure 'greeting' exists and is not null
        $messageText = isset($this->messages['greeting']) ? $this->messages['greeting'] . "\n" : '';
    
        // Append each line from the 'message' array if it exists and is an array
        if (isset($this->messages['message']) && is_array($this->messages['message'])) {
            foreach ($this->messages['message'] as $line) {
                if (!empty($line)) {
                    $messageText .= $line . "\n"; // Add each line with a newline at the end
                }
            }
        }
    
        // Check if scheduled date is set and valid
        if (isset($this->scheduledDate) && $this->scheduledDate instanceof \DateTime) {
            $messageText .= sprintf(
                "Your vaccination is scheduled for %s.",
                $this->scheduledDate->format('l, F j, Y \a\t g:i A')
            );
        }
    
        return trim($messageText); // Return the final message, trimming any excess whitespace
    }
    

}
