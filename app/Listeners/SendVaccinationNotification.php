<?php
namespace App\Listeners;

use App\Events\VaccinationScheduled;
use App\Contracts\NotificationInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVaccinationNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\VaccinationScheduled  $event
     * @return void
     */
    public function handle(VaccinationScheduled $event)
    {
        // Send notification to the user
        $user = $event->user;
        $scheduledDate = $event->scheduledDate;

        // You can reuse your existing notification logic here
        $notificationDate = \Carbon\Carbon::parse($scheduledDate)->subDay()->setTime(21, 0);
        $messages = [
            'greeting' => 'Hello ' . $user->name,
            'message' => [
                'Your vaccination is scheduled for ' . $scheduledDate->format('l, F j, Y \a\t g:i A'),
                'Thanks for your Patience. Please present at your vaccination center in the scheduled time.',
            ],
        ];

        $email_notification = app(NotificationInterface::class, [
            'type' => 'email',
            'user' => $user,
            'scheduledDate' => $scheduledDate,
            'notificationDate' => $notificationDate,
            'messages' => $messages,
        ]);        
        $sms_notification = app(NotificationInterface::class, [
            'type' => 'sms',
            'user' => $user,
            'scheduledDate' => $scheduledDate,
            'notificationDate' => $notificationDate,
            'messages' => $messages,
        ]);
        
        $user->notify($email_notification);
        $user->notify($sms_notification);
        //$user->notify(new EmailNotification($user, $scheduledDate, $notificationDate, $messages));
        //$user->notify(new SmsNotification($user, $scheduledDate, $notificationDate, $messages));
    }
}
