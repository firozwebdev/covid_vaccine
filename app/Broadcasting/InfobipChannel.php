<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class InfobipChannel
{
    public function send($notifiable, Notification $notification)
    {
        // Get the SMS content from the notification
        $message = $notification->toSms($notifiable);

        // Send the SMS using the Infobip API
        $response = Http::withHeaders([
            'Authorization' => 'App ' . config('services.infobip.key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://6992pe.api.infobip.com/sms/2/text/advanced', $message);

        // Handle the response
        if (!$response->successful()) {
            \Log::error('Failed to send SMS. Status: ' . $response->status() . ' Response: ' . $response->body());
        }
    }
}
