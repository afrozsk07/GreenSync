<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WasteRequest;
use App\Models\WasteCollection;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Driver;

class AdminCollectionController extends Controller
{
    public function index()
    {
        $pendingRequests = WasteRequest::where('status', 'pending')->with('user')->get();
        $activeCollections = WasteCollection::whereIn('status', ['scheduled', 'in_progress', 'assigned'])->with('user')->get();
        $completedCollections = WasteCollection::where('status', 'completed')->with('user')->orderBy('created_at', 'desc')->take(10)->get();
        $vehicles = Vehicle::where('status', 'available')->get();
        $drivers = Driver::where('status', 'available')->get();
        
        $stats = [
            'total_requests' => WasteRequest::count(),
            'pending_requests' => WasteRequest::where('status', 'pending')->count(),
            'active_collections' => WasteCollection::whereIn('status', ['scheduled', 'in_progress', 'assigned'])->count(),
            'completed_today' => WasteCollection::where('status', 'completed')->whereDate('created_at', today())->count()
        ];

        return view('admin.collections', compact('pendingRequests', 'activeCollections', 'completedCollections', 'vehicles', 'drivers', 'stats'));
    }

    public function approveRequest($id)
    {
        $request = WasteRequest::findOrFail($id);
        $request->update(['status' => 'approved']);

        // Create collection record
        WasteCollection::create([
            'user_id' => $request->user_id,
            'request_id' => $request->id,
            'waste_type' => $request->waste_type,
            'quantity' => $request->quantity,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'address' => $request->address,
            'status' => 'scheduled'
        ]);

        return redirect()->back()->with('success', 'Request approved and collection scheduled!');
    }

    public function rejectRequest($id)
    {
        $request = WasteRequest::findOrFail($id);
        $request->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'Request rejected!');
    }

    public function assignVehicle(Request $request, $collectionId)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id'
        ]);

        $collection = WasteCollection::findOrFail($collectionId);
        $collection->update([
            'vehicle_id' => $request->vehicle_id,
            'driver_id' => $request->driver_id,
            'status' => 'assigned'
        ]);

        // Update vehicle and driver status
        Vehicle::find($request->vehicle_id)->update(['status' => 'assigned']);
        Driver::find($request->driver_id)->update(['status' => 'assigned']);

        return redirect()->back()->with('success', 'Vehicle and driver assigned successfully!');
    }

    public function startCollection($id)
    {
        $collection = WasteCollection::findOrFail($id);
        $collection->update(['status' => 'in_progress']);
        
        return redirect()->back()->with('success', 'Collection started!');
    }

    public function completeCollection($id)
    {
        $collection = WasteCollection::findOrFail($id);
        $collection->update(['status' => 'completed']);
        
        // Free up vehicle and driver
        if ($collection->vehicle_id) {
            Vehicle::find($collection->vehicle_id)->update(['status' => 'available']);
        }
        if ($collection->driver_id) {
            Driver::find($collection->driver_id)->update(['status' => 'available']);
        }

        return redirect()->back()->with('success', 'Collection completed!');
    }

    public function viewCollectionDetails($id)
    {
        $collection = WasteCollection::with(['user', 'vehicle', 'driver'])->findOrFail($id);
        
        return view('admin.collection-details', compact('collection'));
    }

    public function generateReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30));
        $endDate = $request->get('end_date', now());

        $collections = WasteCollection::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->get();

        $report = [
            'total_collections' => $collections->count(),
            'completed_collections' => $collections->where('status', 'completed')->count(),
            'total_waste_collected' => $collections->sum('quantity'),
            'average_collection_time' => $collections->avg('collection_time'),
            'collections_by_type' => $collections->groupBy('waste_type')
        ];

        return response()->json($report);
    }

    public function manageVehicles()
    {
        $vehicles = Vehicle::with('driver')->get();
        
        return view('admin.vehicles', compact('vehicles'));
    }

    public function manageDrivers()
    {
        $drivers = Driver::with('vehicle')->get();
        
        return view('admin.drivers', compact('drivers'));
    }
} 