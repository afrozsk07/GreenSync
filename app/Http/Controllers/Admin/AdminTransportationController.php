<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transportation;
use App\Models\WasteCollection;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\Location;

class AdminTransportationController extends Controller
{
    public function index(Request $request)
    {
        $activeTransports = Transportation::where('status', 'in_transit')->with(['user', 'vehicle', 'driver'])->get();
        $scheduledTransports = Transportation::where('status', 'scheduled')->with(['user', 'vehicle', 'driver'])->get();
        $completedTransports = Transportation::where('status', 'completed')->with(['user', 'vehicle', 'driver'])->orderBy('created_at', 'desc')->take(10)->get();
        $vehicles = Vehicle::where('status', 'available')->get();
        $drivers = Driver::where('status', 'available')->get();
        $locations = Location::all();
        $activeCollections = \App\Models\WasteCollection::whereIn('status', ['scheduled', 'assigned', 'in_progress'])->with('user')->get();
        $selectedCollectionId = $request->query('collection_id');
        
        $stats = [
            'total_transports' => Transportation::count(),
            'active_transports' => Transportation::where('status', 'in_transit')->count(),
            'scheduled_transports' => Transportation::where('status', 'scheduled')->count(),
            'completed_today' => Transportation::where('status', 'completed')->whereDate('created_at', today())->count()
        ];

        return view('admin.transportation', compact('activeTransports', 'scheduledTransports', 'completedTransports', 'vehicles', 'drivers', 'locations', 'stats', 'activeCollections', 'selectedCollectionId'));
    }

    public function createTransport(Request $request)
    {
        $request->validate([
            'collection_id' => 'required|exists:collections,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'destination_id' => 'required|exists:locations,id',
            'estimated_departure' => 'required|date',
            'estimated_arrival' => 'required|date|after:estimated_departure'
        ]);

        $collection = WasteCollection::findOrFail($request->collection_id);
        
        $transportation = Transportation::create([
            'user_id' => $collection->user_id,
            'collection_id' => $request->collection_id,
            'vehicle_id' => $request->vehicle_id,
            'driver_id' => $request->driver_id,
            'destination_id' => $request->destination_id,
            'estimated_departure' => $request->estimated_departure,
            'estimated_arrival' => $request->estimated_arrival,
            'status' => 'scheduled'
        ]);

        // Update vehicle and driver status
        Vehicle::find($request->vehicle_id)->update(['status' => 'assigned']);
        Driver::find($request->driver_id)->update(['status' => 'assigned']);

        return redirect()->back()->with('success', 'Transportation scheduled successfully!');
    }

    public function startTransport($id)
    {
        $transportation = Transportation::findOrFail($id);
        $transportation->update([
            'status' => 'in_transit',
            'actual_departure' => now()
        ]);
        
        return redirect()->back()->with('success', 'Transportation started!');
    }

    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'current_location' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        $transportation = Transportation::findOrFail($id);
        $transportation->update([
            'current_location' => $request->current_location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'last_updated' => now()
        ]);

        return response()->json(['message' => 'Location updated successfully']);
    }

    public function completeTransport($id)
    {
        $transportation = Transportation::findOrFail($id);
        $transportation->update([
            'status' => 'completed',
            'actual_arrival' => now()
        ]);

        // Free up vehicle and driver
        if ($transportation->vehicle_id) {
            Vehicle::find($transportation->vehicle_id)->update(['status' => 'available']);
        }
        if ($transportation->driver_id) {
            Driver::find($transportation->driver_id)->update(['status' => 'available']);
        }

        return redirect()->back()->with('success', 'Transportation completed!');
    }

    public function viewTransportDetails($id)
    {
        $transportation = Transportation::with(['user', 'vehicle', 'driver', 'destination'])->findOrFail($id);
        
        return view('admin.transport-details', compact('transportation'));
    }

    public function generateTransportReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30));
        $endDate = $request->get('end_date', now());

        $transports = Transportation::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'vehicle', 'driver'])
            ->get();

        $report = [
            'total_transports' => $transports->count(),
            'completed_transports' => $transports->where('status', 'completed')->count(),
            'total_distance' => $transports->sum('distance'),
            'average_transport_time' => $transports->avg('transport_time'),
            'transports_by_vehicle' => $transports->groupBy('vehicle_id')
        ];

        return response()->json($report);
    }

    public function manageRoutes()
    {
        $routes = Transportation::with(['vehicle', 'driver', 'destination'])
            ->where('status', 'in_transit')
            ->get();

        return view('admin.routes', compact('routes'));
    }

    public function trackAllVehicles()
    {
        $vehicles = Vehicle::with(['driver', 'transportation'])
            ->where('status', 'assigned')
            ->get();

        return view('admin.vehicle-tracking', compact('vehicles'));
    }

    public function getVehicleLocation($vehicleId)
    {
        $transportation = Transportation::where('vehicle_id', $vehicleId)
            ->where('status', 'in_transit')
            ->first();

        if (!$transportation) {
            return response()->json(['error' => 'Vehicle not in transit'], 404);
        }

        return response()->json([
            'vehicle_id' => $vehicleId,
            'current_location' => $transportation->current_location,
            'latitude' => $transportation->latitude,
            'longitude' => $transportation->longitude,
            'last_updated' => $transportation->last_updated
        ]);
    }
} 