<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\VonageMessage;
use Vonage\Client\Credentials\Basic;
use Vonage\Client;

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
        // Log the sending process for debugging
        \Log::info('Sending Vonage notification to user ID: ' . $this->user->id . ' at mobile: ' . $this->user->mobile);

        // Concatenate message lines into a single string
        $messageContent = implode(' ', $this->messages['message']); // Assuming 'message' is an array

        // Add scheduled and notification date information to the message
        $messageContent .= sprintf(
            ' Your vaccination is scheduled for %s. You will receive a reminder on %s.',
            $this->scheduledDate->format('l, F j, Y \a\t g:i A') ?? '',
            $this->notificationDate->format('l, F j, Y \a\t g:i A') ?? ''
        );

       // Retrieve Vonage credentials from the config file
       $vonageKey = config('services.vonage.key');
       $vonageSecret = config('services.vonage.secret');

       // Set up the Vonage client using credentials from config/services.php
       $basic = new Basic($vonageKey, $vonageSecret);
       $client = new Client($basic);
       
        // Send the SMS message
        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS($this->user->mobile, 'Vaccination Reminder', $messageContent)
        );

        $message = $response->current();

        // Check if the message was sent successfully
        if ($message->getStatus() == 0) {
            \Log::info("The message was sent successfully to user ID: " . $this->user->id);
        } else {
            \Log::error("The message failed with status: " . $message->getStatus());
        }
    }
}
