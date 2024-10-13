<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SmsChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Get the mobile number from the notifiable entity
        if (! $mobile = $notifiable->routeNotificationFor('sms')) {
            return;
        }

        // Get the message data from the notification
        $message = $notification->toSms($notifiable);

        // Send the SMS request to Infobip
        $response = Http::withHeaders([
            'Authorization' => 'App ' . config('services.infobip.key'),
            'Content-Type' => 'application/json',
        ])->withOptions(['verify' => false])
        ->post(config('services.infobip.base_url') . '/sms/2/text/advanced', $message);

        // Log the response or handle errors
        if ($response->successful()) {
            \Log::info('SMS sent successfully: ' . $response->body());
        } else {
            \Log::error('Failed to send SMS. Status: ' . $response->status() . ' Response: ' . $response->body());
        }
    }
}
