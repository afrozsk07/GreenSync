<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transportation;
use App\Models\WasteCollection;
use App\Models\Vehicle;
use App\Models\Driver;

class TransportationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's collections that have transportation
        $collections = WasteCollection::where('user_id', $user->id)
            ->whereHas('transportation')
            ->with(['transportation', 'vehicle', 'driver'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $activeTransports = $collections->where('transportation.status', 'in_transit');
        $completedTransports = $collections->where('transportation.status', 'completed');
        $scheduledTransports = $collections->where('transportation.status', 'scheduled');
        
        // Statistics
        $stats = [
            'total_transports' => $collections->count(),
            'active_transports' => $activeTransports->count(),
            'completed_transports' => $completedTransports->count(),
            'scheduled_transports' => $scheduledTransports->count()
        ];
        
        return view('user.transportation', compact(
            'user', 
            'collections', 
            'activeTransports', 
            'completedTransports', 
            'scheduledTransports', 
            'stats'
        ));
    }

    public function trackTransport($collectionId)
    {
        $collection = WasteCollection::where('id', $collectionId)
            ->where('user_id', Auth::id())
            ->with(['transportation', 'vehicle', 'driver', 'request'])
            ->first();
        
        if (!$collection) {
            return redirect()->back()->with('error', 'Collection not found.');
        }

        if (!$collection->transportation) {
            return redirect()->back()->with('error', 'No transportation record found for this collection.');
        }

        // Get tracking information
        $trackingInfo = [
            'collection_id' => $collection->id,
            'waste_type' => $collection->waste_type,
            'quantity' => $collection->quantity,
            'pickup_address' => $collection->address,
            'transport_status' => $collection->transportation->status,
            'estimated_departure' => $collection->transportation->estimated_departure,
            'estimated_arrival' => $collection->transportation->estimated_arrival,
            'actual_departure' => $collection->transportation->actual_departure,
            'actual_arrival' => $collection->transportation->actual_arrival,
            'current_location' => $collection->transportation->current_location,
            'vehicle_info' => $collection->vehicle ? $collection->vehicle->vehicle_number : 'Not assigned',
            'driver_info' => $collection->driver ? $collection->driver->name : 'Not assigned'
        ];

        return view('user.track-transport', compact('collection', 'trackingInfo'));
    }

    public function viewTransportHistory()
    {
        $user = Auth::user();
        
        $collections = WasteCollection::where('user_id', $user->id)
            ->whereHas('transportation')
            ->with(['transportation', 'vehicle', 'driver'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('user.transport-history', compact('collections'));
    }

    public function getTransportStatus($collectionId)
    {
        $collection = WasteCollection::where('id', $collectionId)
            ->where('user_id', Auth::id())
            ->with(['transportation', 'vehicle', 'driver'])
            ->first();
        
        if (!$collection || !$collection->transportation) {
            return response()->json(['error' => 'Transportation not found'], 404);
        }

        return response()->json([
            'status' => $collection->transportation->status,
            'current_location' => $collection->transportation->current_location,
            'estimated_departure' => $collection->transportation->estimated_departure,
            'estimated_arrival' => $collection->transportation->estimated_arrival,
            'actual_departure' => $collection->transportation->actual_departure,
            'actual_arrival' => $collection->transportation->actual_arrival,
            'driver_name' => $collection->driver ? $collection->driver->name : 'Not assigned',
            'vehicle_number' => $collection->vehicle ? $collection->vehicle->vehicle_number : 'Not assigned'
        ]);
    }

    public function viewRoute($collectionId)
    {
        $collection = WasteCollection::where('id', $collectionId)
            ->where('user_id', Auth::id())
            ->with(['transportation', 'vehicle', 'driver'])
            ->first();
        
        if (!$collection || !$collection->transportation) {
            return redirect()->back()->with('error', 'Transportation record not found.');
        }

        return view('user.transport-route', compact('collection'));
    }

    public function getActiveTransports()
    {
        $user = Auth::user();
        
        $activeTransports = WasteCollection::where('user_id', $user->id)
            ->whereHas('transportation', function($query) {
                $query->where('status', 'in_transit');
            })
            ->with(['transportation', 'vehicle', 'driver'])
            ->get();

        return response()->json($activeTransports);
    }
}
