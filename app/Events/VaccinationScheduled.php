<?php
namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VaccinationScheduled
{
    use Dispatchable, SerializesModels;

    public $user;
    public $scheduledDate;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User  $user
     * @param  \Carbon\Carbon  $scheduledDate
     * @return void
     */
    public function __construct(User $user, $scheduledDate)
    {
        $this->user = $user;
        $this->scheduledDate = $scheduledDate;
    }
}
