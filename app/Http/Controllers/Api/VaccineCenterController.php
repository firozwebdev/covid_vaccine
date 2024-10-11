<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VaccineCenter;
use App\Actions\StoreUserAction;
use App\Http\Requests\UserRequest;
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

    public function getUsers()
    {
       
        // Fetch all users with their vaccine center details
        $users = User::with('vaccineCenter')->get();
        
        // Return as JSON response
        return response()->json([
            'users' => $users,
        ], 200);
    }

    public function register(UserRequest $request, StoreUserAction $storeUserAction)
    {
        
        $user = User::where('nid', $request->nid)->first();
        \Log::info($request->all());
        if ($user) {
            return response()->json([
                'message' => 'Registration successful! Check your status.',
                'status' => 'already_registered',
                'user' => $user,
                
            ], 200);
        }
        // Find the selected vaccine center
        $vaccineCenter = VaccineCenter::find($request->vaccine_center_id);
        
        // Check current registrations against daily limit
        $currentRegistrations = $vaccineCenter->users()->count(); // Get the current registrations

        if ($currentRegistrations >= $vaccineCenter->daily_limit) {
            return response()->json([
                'message' => 'Registration limit for this vaccine center has been reached. Please choose another center.',
            ], 400);
        }
    
        
        $user = $storeUserAction->execute($request->toDTO());
       

        return response()->json([
            'message' => 'Registration successful! Check your status.',
            'status' => 'registered',
            'user' => $user,
            
        ], 201);
    }

    
}
