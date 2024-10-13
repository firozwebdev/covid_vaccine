<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Console\Command;
use App\Models\VaccinationSchedule;
use App\Contracts\NotificationInterface;

class ScheduleVaccination extends Command
{
    protected $signature = 'give:schedule';
    protected $description = 'Schedule vaccinations for users based on first come, first serve and only on weekdays (Sunday to Thursday)';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting to schedule vaccinations...');

        // Fetch users who are not scheduled for vaccination yet
        $users = User::where('status', 'Not scheduled')
                     ->orderBy('created_at', 'asc') // First come, first serve
                     ->get();

        // Fetch all vaccine centers
        $centers = VaccineCenter::all();

        foreach ($users as $user) {
            // Get the center based on your logic, e.g., first available center
            $center = VaccineCenter::find($user->vaccine_center_id);
        
            // Check if a center was found and it has capacity
            if ($center && $this->hasCapacity($center)) {
                // Log the values for debugging
                \Log::info("Scheduling: User ID: {$user->id}, Center ID: {$center->id}");

                // Schedule the vaccination
                $scheduledDate = $this->findNextAvailableWeekday($center);

                VaccinationSchedule::create([
                    'user_id' => $user->id,
                    'vaccine_center_id' => $center->id,
                    'scheduled_date' => $scheduledDate, // Ensure this is set
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update user status
                $user->status = 'Scheduled';
                $user->save();

                // Send notifications to the user
                $this->sendNotifications($user, $scheduledDate);
            } else {
                \Log::error("No available slots for user {$user->name} (ID: {$user->id}) or center not found.");
            }
        }

        $this->info('Scheduling completed.');
    }

    // Helper function to check if a center has capacity for the day
    protected function hasCapacity($center)
    {
        // Check the number of scheduled vaccinations for today
        $scheduledCount = VaccinationSchedule::where('vaccine_center_id', $center->id)
                        ->whereDate('scheduled_date', Carbon::today())
                        ->count();

        return $scheduledCount < $center->daily_limit; // Use daily_limit instead of capacity
    }

    // Helper function to find the next available weekday for vaccination
    protected function findNextAvailableWeekday($center)
    {
        $date = Carbon::today();

        while (true) {
            // Check if the date is a weekday (Sunday to Thursday)
            if ($this->isWeekday($date)) {
                // Check the scheduled count for this date
                $scheduledCount = VaccinationSchedule::where('vaccine_center_id', $center->id)
                                ->whereDate('scheduled_date', $date)
                                ->count();

                if ($scheduledCount < $center->daily_limit) {
                    return $date; // Return available date
                }
            }

            // Move to the next day
            $date->addDay();
        }
    }

    // Helper function to check if a given date is a weekday (Sunday to Thursday)
    protected function isWeekday($date)
    {
        // In Laravel, Carbon's 'dayOfWeek' returns 0 for Sunday, 1 for Monday, ..., 4 for Thursday
        return $date->dayOfWeek <= 4; // 0 (Sunday) to 4 (Thursday)
    }

    // Function to handle notifications after scheduling
    protected function sendNotifications($user, $scheduledDate)
    {
        $notificationDate = Carbon::parse($scheduledDate)->subDay()->setTime(21, 0); // Notify the day before at 9 PM

        $messages = [
            'greeting' => 'Hello ' . $user->name,
            'message' => [
                'Your vaccination is scheduled for ' . $scheduledDate->format('l, F j, Y \a\t g:i A'),
                'Thanks for your patience. Please present at your vaccination center at the scheduled time.',
            ],
        ];

        // Send email notification
        $emailNotification = app(NotificationInterface::class, [
            'type' => 'email',
            'user' => $user,
            'scheduledDate' => $scheduledDate,
            'notificationDate' => $notificationDate,
            'messages' => $messages,
        ]);

        // Send SMS notification
        $smsNotification = app(NotificationInterface::class, [
            'type' => 'sms',
            'user' => $user,
            'scheduledDate' => $scheduledDate,
            'notificationDate' => $notificationDate,
            'messages' => $messages,
        ]);

        $user->notify($emailNotification);
        $user->notify($smsNotification);
    }
}
