<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VaccineCenter;
use App\Events\UserRegistered;
use App\Actions\StoreUserAction;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

class RegistrationController extends Controller
{
    public function register(UserRequest $request, StoreUserAction $storeUserAction)
    {
        $user = User::where('nid', $request->nid)->first();
        if ($user) {
            return response()->json([
                'message' => 'Registration successful! Check your status.',
                'status' => 'already_registered',
                'user' => $user,
            ], 200);
        }
    
        $vaccineCenter = VaccineCenter::find($request->vaccine_center_id);
        $currentRegistrations = $vaccineCenter->users()->count();
        
        if ($currentRegistrations >= $vaccineCenter->daily_limit) {
            return response()->json([
                'message' => 'Registration limit for this vaccine center has been reached. Please choose another center.',
            ], 400);
        }
    
        // Register the user
        $user = $storeUserAction->execute($request->toDTO());
    
        // Dispatch the event to handle post-registration tasks
        event(new UserRegistered($user));
    
        return response()->json([
            'message' => 'Registration successful! Check your status.',
            'status' => 'registered',
            'user' => $user,
        ], 201);
    }
}
