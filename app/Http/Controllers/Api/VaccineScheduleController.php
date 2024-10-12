<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VaccinationSchedule;
use App\Http\Controllers\Controller;
use App\Notifications\VaccinationReminder;

class VaccineScheduleController extends Controller
{
    public function scheduleVaccination(Request $request)
    {
        // Find the user by ID
        \Log::info($request->user_id);
        $user_id = $request->user_id;
       
        $scheduledDate = $request->scheduled_date;
        //Validate the incoming request
        $request->validate([
            'scheduled_date' => 'required|date|after:today',
        ]);
    
        
    
        // Check if the user exists
        if (!$user_id) {
            \Log::error("User not found with ID: $user_id");
            return response()->json(['message' => 'User not found.'], 404);
        }
        // Fetch the user
        $user = User::find($user_id);
        $center = $user->vaccineCenter;

        // Ensure the scheduled date falls on a weekday (Sunday to Thursday)
        $date = Carbon::parse($scheduledDate);
        if ($date->isWeekend()) {
            // Adjust the date to the next available weekday
            $date = $this->getNextWeekday($date);
        }

        // Find the next available date for vaccination
        $nextAvailableDate = VaccinationSchedule::where('vaccine_center_id', $center->id)
            ->where('scheduled_date', '>=', now())
            ->groupBy('scheduled_date')
            ->havingRaw('COUNT(*) < ?', [$center->daily_limit])
            ->orderBy('scheduled_date', 'asc')
            ->first()->scheduled_date ?? $date;

        // Create a new vaccination schedule
        VaccinationSchedule::create([
            'user_id' => $user->id,
            'vaccine_center_id' => $center->id,
            'scheduled_date' => $date,
        ]);

        // Update the user's status
        $user->update([
            'scheduled_date' => $date,
            'status' => 'Scheduled',
        ]);

        // Schedule notification for the user
        $this->scheduleNotification($user, $user->scheduled_date);
    }

    private function scheduleNotification(User $user, $scheduledDate)
    {
        // Calculate the notification date (9 PM the night before the scheduled date)
        $notificationDate = Carbon::parse($scheduledDate)->subDay()->setTime(21, 0);

        // Notify the user with both scheduled and notification dates
        $user->notify(new VaccinationReminder($user, $scheduledDate, $notificationDate));
    }

    private function getNextWeekday(Carbon $date)
    {
        do {
            $date = $date->addDay();
        } while ($date->isFriday() || $date->isSaturday()); // Skip Friday and Saturday

        return $date;
    }
}
