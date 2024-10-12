<?php

namespace App\Providers;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function () {
            $basic  = new Basic(config('services.nexmo.key'), config('services.nexmo.secret'));
            return new Client($basic);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
