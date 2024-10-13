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
        // Start with the greeting
        $messageText = $this->messages['greeting'] . "\n"; // Add the greeting with a newline

        // Append each line from the 'message' array
        foreach ($this->messages['message'] as $line) {
            if (!empty($line)) {
                $messageText .= $line . "\n"; // Add each line with a newline at the end
            }
        }

        // Optionally, add any other dynamic content, like scheduled or notification dates
        $messageText .= sprintf(
            "Your vaccination is scheduled for %s.",
            $this->scheduledDate->format('l, F j, Y \a\t g:i A')
        );

        return trim($messageText); // Return the final message, trimming any excess whitespace
    }

}
