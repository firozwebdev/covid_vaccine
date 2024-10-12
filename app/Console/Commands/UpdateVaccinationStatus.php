<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateVaccinationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:update-vaccination-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update vaccination status for users whose scheduled date was yesterday.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Update users whose scheduled date is in the past and not already marked as 'Vaccinated'
        $updatedCount = User::where('status', '!=', 'Vaccinated')
            ->where('scheduled_date', '<', now()->toDateTimeString())
            ->update(['status' => 'Vaccinated']);

        // Log the number of users updated
        \Log::info('Updated users who were scheduled for vaccination in the past: ' . $updatedCount);
        
        // Optional: Provide feedback in the console
        $this->info("Successfully updated {$updatedCount} users to 'Vaccinated' status.");
    }
}
