<?php
namespace App\Providers;

use App\Events\UserRegistered;
use App\Events\VaccinationScheduled;
use App\Listeners\SendVaccinationNotification;
use App\Listeners\SendRegistrationNotifications;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        VaccinationScheduled::class => [
            SendVaccinationNotification::class,
        ],
        UserRegistered::class => [
            SendRegistrationNotifications::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
