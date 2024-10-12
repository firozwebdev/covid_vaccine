<?php
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ScheduleVaccinationReminder;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run the command manually
// Artisan::command('schedule:vaccination-reminder', function () {
//     $this->call(ScheduleVaccinationReminder::class);
// })->purpose('Send vaccination notifications to users scheduled for tomorrow at 9 PM.');

// Optionally, if you want to schedule the command
//$schedule->command('schedule:vaccination-reminder')->dailyAt('21:00');

//app(Schedule::class)->command('schedule:vaccination-reminder')->dailyAt('21:00');
app(Schedule::class)->command('schedule:vaccination-reminder')->dailyAt('21:00');
// Register the command to run daily
//app(Schedule::class)->command('status:update-vaccination-status')->daily()->at('00:00'); // Run daily at midnight
app(Schedule::class)->command('status:update-vaccination-status')->everyMinute(); // Run daily at midnight
