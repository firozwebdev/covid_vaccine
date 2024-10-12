<?php
namespace App\Listeners;

use App\Events\VaccinationScheduled;
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

        $user->notify(new \App\Notifications\EmailNotification($user, $scheduledDate, $notificationDate));
    }
}
