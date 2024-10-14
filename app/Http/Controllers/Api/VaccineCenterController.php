<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VaccineCenter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class VaccineCenterController extends Controller
{
    /**
     * Display a listing of the VaccineCenter.
     */
    public function getVaccineCenters()
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

    public function getUsers(Request $request)
    {
        // Define how many users to retrieve per page
        $perPage = $request->input('per_page', 20); // Default to 10 if not specified
    
        // Cache key based on pagination and selected fields to improve performance
        $cacheKey = "users_page_" . $request->input('page', 1) . "_per_" . $perPage;
    
        // Start to retrieve from cache in Production environment
        // $users = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage) {
        //     // Fetch paginated users with their vaccine center details, selecting necessary fields
        //     return User::with(['vaccineCenter:id,name']) // Adjust fields based on your VaccineCenter model
        //         ->select('id', 'name', 'email', 'nid', 'mobile', 'vaccine_center_id', 'status', 'scheduled_date')
        //         ->paginate($perPage); // Use pagination
        // });
        // End to retrieve from cache in Production environment

        // Start to retrieve from only DB in Development environment
        $users  = User::with(['vaccineCenter:id,name'])
                        ->select('id', 'name', 'email', 'nid', 'mobile', 'vaccine_center_id', 'status', 'scheduled_date')
                        ->orderBy('id', 'desc')
                        ->paginate($perPage);
    
        // Return as JSON response with pagination details
        return response()->json([
            'users' => $users,
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ], 200);
    }

    
    

   

    
}
