<?php

namespace App\Notifications;

use App\Contracts\NotificationInterface;
use Illuminate\Notifications\Messages\MailMessage;

class EmailNotification implements NotificationInterface
{
    public function send($notifiable, $message)
    {
        \Log::info('Preparing to send email', [
            'to' => $notifiable->email,
            'message' => $message,
        ]);
        return (new MailMessage)
            ->greeting('Hello ' . $notifiable->name)
            ->line($message)
            ->line('Thanks for your patience!');
    }
}
