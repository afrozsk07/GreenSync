<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WasteCollection;
use App\Models\WasteRequest;

class CollectionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userAddresses = $user->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'asc')->get();
        
        // Get user's collection requests and collections
        $pendingRequests = WasteRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $approvedRequests = WasteRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $collections = WasteCollection::where('user_id', $user->id)
            ->with(['transportation', 'vehicle', 'driver'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $completedCollections = $collections->where('status', 'completed');
        $activeCollections = $collections->whereIn('status', ['scheduled', 'assigned', 'in_progress']);
        
        // Statistics
        $stats = [
            'total_requests' => WasteRequest::where('user_id', $user->id)->count(),
            'pending_requests' => $pendingRequests->count(),
            'completed_collections' => $completedCollections->count(),
            'total_waste_collected' => $completedCollections->sum('quantity'),
            'active_collections' => $activeCollections->count()
        ];
        
        return view('user.collections', compact(
            'user', 
            'userAddresses',
            'pendingRequests', 
            'approvedRequests', 
            'collections', 
            'completedCollections', 
            'activeCollections', 
            'stats'
        ));
    }

    public function showRequestForm()
    {
        $user = Auth::user();
        $userAddresses = $user->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'asc')->get();
        
        return view('user.request-collection', compact('user', 'userAddresses'));
    }

    public function requestCollection(Request $request)
    {
        $request->validate([
            'waste_type' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.1|max:1000',
            'pickup_date' => 'required|date|after:today',
            'pickup_time' => 'nullable',
            'address' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
            'priority' => 'nullable|in:low,medium,high',
            'special_instructions' => 'nullable|string|max:500'
        ]);

        WasteRequest::create([
            'user_id' => Auth::id(),
            'waste_type' => $request->waste_type,
            'quantity' => $request->quantity,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time ?: null,
            'address' => $request->address,
            'description' => $request->description,
            'priority' => $request->priority ?? 'medium',
            'special_instructions' => $request->special_instructions,
            'status' => 'pending'
        ]);

        return redirect()->route('collections.request.form')->with('success', 'Collection request submitted successfully! We will review and get back to you soon.');
    }

    public function cancelRequest($id)
    {
        $wasteRequest = WasteRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$wasteRequest) {
            return redirect()->back()->with('error', 'Request not found.');
        }
        
        if ($wasteRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending requests can be cancelled.');
        }

        $wasteRequest->update(['status' => 'cancelled']);
        return redirect()->back()->with('success', 'Request cancelled successfully!');
    }

    public function viewHistory()
    {
        $user = Auth::user();
        
        $collections = WasteCollection::where('user_id', $user->id)
            ->with(['request', 'vehicle', 'driver'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $requests = WasteRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('user.collection-history', compact('collections', 'requests'));
    }

    public function trackStatus($id)
    {
        $collection = WasteCollection::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['request', 'vehicle', 'driver', 'transportation'])
            ->first();
        
        if (!$collection) {
            return redirect()->back()->with('error', 'Collection not found.');
        }

        // Get comprehensive tracking information
        $trackingInfo = [
            // Collection Information
            'collection_status' => $collection->status,
            'pickup_date' => $collection->pickup_date,
            'pickup_time' => $collection->pickup_time,
            'address' => $collection->address,
            'waste_type' => $collection->waste_type,
            'quantity' => $collection->quantity,
            'collection_notes' => $collection->collection_notes,
            'actual_pickup_time' => $collection->actual_pickup_time,
            'completion_time' => $collection->completion_time,
            
            // Vehicle and Driver Information
            'vehicle_info' => $collection->vehicle ? $collection->vehicle->vehicle_number : 'Not assigned',
            'driver_info' => $collection->driver ? $collection->driver->name : 'Not assigned',
            
            // Transportation Information (if available)
            'transport_status' => $collection->transportation ? $collection->transportation->status : null,
            'estimated_departure' => $collection->transportation ? $collection->transportation->estimated_departure : null,
            'estimated_arrival' => $collection->transportation ? $collection->transportation->estimated_arrival : null,
            'actual_departure' => $collection->transportation ? $collection->transportation->actual_departure : null,
            'actual_arrival' => $collection->transportation ? $collection->transportation->actual_arrival : null,
            'current_location' => $collection->transportation ? $collection->transportation->current_location : null,
            'destination' => $collection->transportation && $collection->transportation->destination ? $collection->transportation->destination->name : null,
        ];

        return view('user.track-collection', compact('collection', 'trackingInfo'));
    }

    public function getRequestDetails($id)
    {
        $request = WasteRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$request) {
            return response()->json(['error' => 'Request not found'], 404);
        }

        return response()->json([
            'id' => $request->id,
            'waste_type' => $request->waste_type,
            'quantity' => $request->quantity,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'address' => $request->address,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'special_instructions' => $request->special_instructions,
            'created_at' => $request->created_at->format('M d, Y H:i'),
            'updated_at' => $request->updated_at->format('M d, Y H:i')
        ]);
    }
}
