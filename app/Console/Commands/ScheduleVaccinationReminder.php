<?php
namespace App\Console\Commands;

use Log;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use App\Contracts\NotificationInterface;
use App\Notifications\EmailNotification;

class ScheduleVaccinationReminder extends Command
{
    protected $signature = 'schedule:vaccination-reminder';
    protected $description = 'Send vaccination notifications to users scheduled for tomorrow at 9 PM.';

    public function handle()
    {
        \Log::info('Running vaccination notification scheduler');

        // Process users in chunks to avoid memory overload
        User::whereDate('scheduled_date', '=', now()->addDay()->toDateString())
            ->where('notified', false)
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    $notificationDate = Carbon::parse($user->scheduled_date)->subDay()->setTime(21, 0);

                    if (now()->greaterThanOrEqualTo($notificationDate)) {
                        $messages = [
                            'greeting' => 'Hello ' . $user->name,
                            'message' => [
                                'Your vaccination is scheduled for tomorrow ' . $user->scheduled_date->format('l, F j, Y \a\t g:i A'),
                                'Please come to your vaccination center at the scheduled time.',
                            ],
                        ];
                        
                        // Send notification
                        // $user->notify(new EmailNotification($user, $user->scheduled_date, $notificationDate, $messages));
                        $email_notification = app(NotificationInterface::class, [
                            'type' => 'email',
                            'user' => $user,
                            'notificationDate' => $notificationDate,
                            'messages' => $messages,
                        ]);
                        $sms_notification = app(NotificationInterface::class, [
                            'type' => 'sms',
                            'user' => $user,
                            'notificationDate' => $notificationDate,
                            'messages' => $messages,
                        ]);
                        
                        $user->notify($email_notification);
                        $user->notify($sms_notification);

                        // Log notification sending
                        \Log::info("Notification sent to user ID: {$user->id} for vaccination on {$user->scheduled_date}");

                        // Mark user as notified
                        $user->notified = true;
                        $user->save();
                    }
                }
            });

        \Log::info('Vaccination notification scheduler finished.');
    }
}
