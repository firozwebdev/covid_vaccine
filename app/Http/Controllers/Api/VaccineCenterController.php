<?php

namespace App\Http\Controllers\Api;

use App\Models\VaccineCenter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VaccineCenterController extends Controller
{
    /**
     * Display a listing of the VaccineCenter.
     */
    public function index()
    {
        $vaccineCenters = VaccineCenter::withCount('users') // Assuming there's a registrations relationship
            ->get()
            ->map(function ($center) {
                $currentRegistrations = $center->users_count; // Get the count of registrations
                $availableSlots = $center->daily_limit - $currentRegistrations; // Calculate available slots
    
                return [
                    'id' => $center->id,
                    'name' => $center->name,
                    'daily_limit' => $center->daily_limit,
                    'current_registrations' => $currentRegistrations,
                    'available_slots' => max(0, $availableSlots), // Ensure slots are not negative
                ];
            });
    
        return response()->json(['data' => ['vaccineCenters' => $vaccineCenters]]);
    }

    
}
