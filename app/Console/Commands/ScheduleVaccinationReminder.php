<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\VaccinationReminder;
use Carbon\Carbon;
use Log;

class ScheduleVaccinationReminder extends Command
{
    protected $signature = 'schedule:vaccination-reminder';
    protected $description = 'Send vaccination notifications to users scheduled for tomorrow at 9 PM.';

    public function handle()
    {
        Log::info('Running vaccination notification scheduler');

        // Query users who have vaccination scheduled tomorrow
        $users = User::whereDate('scheduled_date', '=', now()->addDay()->toDateString())->get();

        foreach ($users as $user) {
            // Calculate the notification time (9 PM the night before the scheduled date)
            $notificationDate = Carbon::parse($user->scheduled_date)->subDay()->setTime(21, 0);

            // Check if the notification should be sent
            if (now()->greaterThanOrEqualTo($notificationDate) && !$user->notified) {
                $user->notify(new VaccinationReminder($user, $user->scheduled_date, $notificationDate));

                // Log and mark the user as notified
                Log::info("Notification sent to user ID: {$user->id} for vaccination on {$user->scheduled_date}");

                // Mark user as notified
                $user->notified = true;
                $user->save();
            }
        }
    }
}
