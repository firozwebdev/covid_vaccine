<?php
namespace App\Providers;

use App\Events\VaccinationScheduled;
use App\Listeners\SendVaccinationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        VaccinationScheduled::class => [
            SendVaccinationNotification::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
