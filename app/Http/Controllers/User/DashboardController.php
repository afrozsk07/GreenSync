<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WasteRequest;
use App\Models\WasteCollection;
use App\Models\Transportation;

use App\Models\Segregation;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if ($user->is_admin == 1) {
            return redirect()->route('admin.admin.dashboard');
        }
        
        // Get user statistics
        $stats = $this->getUserStats($user->id);
        
        // Get recent activities
        $recentRequests = WasteRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $recentCollections = WasteCollection::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $pendingRequests = WasteRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        $activeCollections = WasteCollection::where('user_id', $user->id)
            ->whereIn('status', ['scheduled', 'assigned', 'in_progress'])
            ->count();

        return view('user.dashboard', compact(
            'user', 
            'stats', 
            'recentRequests', 
            'recentCollections', 
            'pendingRequests', 
            'activeCollections'
        ));
    }

    private function getUserStats($userId)
    {
        return [
            'total_requests' => WasteRequest::where('user_id', $userId)->count(),
            'completed_collections' => WasteCollection::where('user_id', $userId)
                ->where('status', 'completed')
                ->count(),
            'total_waste_collected' => WasteCollection::where('user_id', $userId)
                ->where('status', 'completed')
                ->sum('quantity'),
            'pending_requests' => WasteRequest::where('user_id', $userId)
                ->where('status', 'pending')
                ->count(),
            'active_transports' => Transportation::where('user_id', $userId)
                ->where('status', 'in_transit')
                ->count(),
            'total_disposals' => 0,
            'segregation_accuracy' => Segregation::where('user_id', $userId)
                ->avg('accuracy') ?? 0,
            'environmental_impact' => $this->calculateEnvironmentalImpact($userId)
        ];
    }

    private function calculateEnvironmentalImpact($userId)
    {
        $totalWaste = WasteCollection::where('user_id', $userId)
            ->where('status', 'completed')
            ->sum('quantity');
            
        $recycledWaste = 0;

        return [
            'total_waste_kg' => $totalWaste,
            'recycled_waste_kg' => $recycledWaste,
            'co2_saved_kg' => $recycledWaste * 2.5, // Estimated CO2 saved per kg
            'trees_equivalent' => round(($recycledWaste * 2.5) / 22, 2) // CO2 absorbed by trees
        ];
    }
}
