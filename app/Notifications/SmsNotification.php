<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $scheduledDate;
    protected $notificationDate;
    protected $messages;

    public function __construct($user, $scheduledDate, $notificationDate, $messages)
    {
        \Log::info('Sending SMS notification to user ID: ' . $user->id);
        $this->user = $user;
        $this->scheduledDate = $scheduledDate;
        $this->notificationDate = $notificationDate;
        $this->messages = $messages;
    }

    public function via($notifiable)
    {
        return ['sms']; // Use the custom channel name
    }

    public function toSms($notifiable)
    {
        // Prepare the message body
        $messageBody = [
            'messages' => [
                [
                    'destinations' => [
                        ['to' => $this->user->mobile], // Ensure you have the correct phone number field
                    ],
                    'from' => config('services.sms.sms_from'), // Use your Infobip config
                    'text' => sprintf(
                        "Congratulations on sending your first message.\nYour vaccination is scheduled for %s. You will receive a reminder on %s.",
                        $this->scheduledDate->format('l, F j, Y \a\t g:i A'),
                        $this->notificationDate->format('l, F j, Y \a\t g:i A')
                    ),
                ],
            ],
        ];

        // Send the SMS request
        return $this->sendSms($messageBody);
    }

    protected function sendSms(array $messageBody)
    {
        // Send the SMS request to Infobip
        $response = Http::withHeaders([
            'Authorization' => 'App ' . config('services.sms.key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post(config('services.sms.base_url') . '/sms/2/text/advanced', $messageBody);

        // Log the response or handle errors as needed
        if ($response->successful()) {
            \Log::info('SMS sent successfully: ' . $response->body());
        } else {
            \Log::error('Failed to send SMS. Status: ' . $response->status() . ' Response: ' . $response->body());
        }
    }

}
