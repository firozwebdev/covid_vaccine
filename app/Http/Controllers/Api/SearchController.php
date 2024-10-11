<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
class SearchController extends Controller
{
    public function searchStatus($nid)
    {
        // Check if user data is cached first
        $user = Cache::remember("user_nid_{$nid}", 60 * 60, function () use ($nid) {
            return User::where('nid', $nid)
                ->select('id', 'name', 'status', 'scheduled_date', 'vaccine_center_id') // Select only necessary columns
                ->with('vaccineCenter:id,name')  // Eager loading the vaccineCenter relationship
                ->first();
        });
    
        if (!$user) {
            return response()->json([
                'message' => 'Not registered',
                'status' => 'not_registered',
            ], 404);
        }
    
        // Check and update status if necessary
        $status = $user->status;
        if ($status == 'Scheduled' && $user->scheduled_date < now()) {
            $status = 'Vaccinated';
            $user->update(['status' => 'Vaccinated']);
    
            // Update the cache after modifying the status
            Cache::put("user_nid_{$nid}", $user, 60 * 60);
        }
    
        return response()->json([
            'user' => $user,
            'status' => $status,
        ], 200);
    }
    
}
