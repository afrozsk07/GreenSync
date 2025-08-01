<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transportation Management - GreenSync Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .main-content {
            padding: 2rem 0;
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .content-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .btn-custom {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
        .btn-success-custom {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-warning-custom {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-scheduled { background: #fff3cd; color: #856404; }
        .status-in-transit { background: #cce5ff; color: #004085; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        
        .logout-btn {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 500;
        }
        
        .logout-btn:hover {
            background: linear-gradient(45deg, #ee5a24, #ff6b6b);
            color: white;
            transform: translateY(-2px);
        }
        
        .form-control {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 12px 15px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('admin.admin.dashboard') }}">
                <i class="fas fa-recycle text-success me-2"></i>
                GreenSync Admin
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user-circle me-2"></i>
                    Welcome, {{ Auth::user()->name }}
                </span>
                <a href="{{ route('admin.admin-logout') }}" class="btn logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container main-content">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-card p-4">
                    <h1 class="text-primary fw-bold mb-3">
                        <i class="fas fa-truck me-3"></i>Transportation Management
                    </h1>
                    <p class="text-muted">Manage waste transportation and track vehicle movements</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-primary">{{ $stats['total_transports'] }}</div>
                        <div class="stats-label">Total Transports</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-info">{{ $stats['active_transports'] }}</div>
                        <div class="stats-label">Active Transports</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-warning">{{ $stats['scheduled_transports'] }}</div>
                        <div class="stats-label">Scheduled</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-success">{{ $stats['completed_today'] }}</div>
                        <div class="stats-label">Completed Today</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create New Transportation -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-card p-4">
                    <h3 class="text-primary fw-bold mb-3">
                        <i class="fas fa-plus me-2"></i>Create New Transportation
                    </h3>
                    <form action="{{ route('admin.admin.transportation.create.post') }}" method="POST">
            @csrf
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="collection_id" class="form-label">Collection</label>
                                <select class="form-control" id="collection_id" name="collection_id" required>
                                    <option value="">Select Collection</option>
                                    @foreach($activeCollections ?? [] as $collection)
                                        <option value="{{ $collection->id }}" {{ (isset($selectedCollectionId) && $selectedCollectionId == $collection->id) ? 'selected' : '' }}>
                                            {{ $collection->user->name }} - {{ $collection->waste_type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="vehicle_id" class="form-label">Vehicle</label>
                                <select class="form-control" id="vehicle_id" name="vehicle_id" required>
                                    <option value="">Select Vehicle</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="driver_id" class="form-label">Driver</label>
                                <select class="form-control" id="driver_id" name="driver_id" required>
                                    <option value="">Select Driver</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="destination_id" class="form-label">Destination</label>
                                <select class="form-control" id="destination_id" name="destination_id" required>
                                    <option value="">Select Destination</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="estimated_departure" class="form-label">Estimated Departure</label>
                                <input type="datetime-local" class="form-control" id="estimated_departure" name="estimated_departure" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estimated_arrival" class="form-label">Estimated Arrival</label>
                                <input type="datetime-local" class="form-control" id="estimated_arrival" name="estimated_arrival" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-custom">
                                <i class="fas fa-plus me-2"></i>Create Transportation
                            </button>
                        </div>
        </form>
                </div>
            </div>
        </div>

        <!-- Active Transports -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-card p-4">
                    <h3 class="text-info fw-bold mb-3">
                        <i class="fas fa-truck me-2"></i>Active Transports
                    </h3>
                    @if($activeTransports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-info">
                                    <tr>
                                        <th>User</th>
                                        <th>Waste Type</th>
                                        <th>Vehicle</th>
                                        <th>Driver</th>
                                        <th>Destination</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeTransports as $transport)
                                    <tr>
                                        <td>{{ $transport->user->name }}</td>
                                        <td>{{ $transport->collection->waste_type ?? 'N/A' }}</td>
                                        <td>{{ $transport->vehicle->name ?? 'Not Assigned' }}</td>
                                        <td>{{ $transport->driver->name ?? 'Not Assigned' }}</td>
                                        <td>{{ $transport->destination->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="status-badge status-{{ str_replace('_', '-', $transport->status) }}">
                                                {{ ucfirst(str_replace('_', ' ', $transport->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($transport->status == 'scheduled')
                                                <a href="{{ route('admin.admin.transportation.start', $transport->id) }}" 
                                                   class="btn btn-custom btn-sm">
                                                    <i class="fas fa-play me-1"></i>Start
                                                </a>
                                            @elseif($transport->status == 'in_transit')
                                                <a href="{{ route('admin.admin.transportation.complete', $transport->id) }}" 
                                                   class="btn btn-success-custom btn-sm">
                                                    <i class="fas fa-check me-1"></i>Complete
                                                </a>
                                                <button class="btn btn-warning-custom btn-sm" onclick="updateLocation({{ $transport->id }})">
                                                    <i class="fas fa-map-marker-alt me-1"></i>Update Location
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.admin.transportation.details', $transport->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>Details
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-truck fa-3x mb-3"></i>
                            <p>No active transports at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Scheduled Transports -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-card p-4">
                    <h3 class="text-warning fw-bold mb-3">
                        <i class="fas fa-clock me-2"></i>Scheduled Transports
                    </h3>
                    @if($scheduledTransports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-warning">
                                    <tr>
                                        <th>User</th>
                                        <th>Waste Type</th>
                    <th>Vehicle</th>
                    <th>Driver</th>
                                        <th>Destination</th>
                                        <th>Departure</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                                    @foreach($scheduledTransports as $transport)
                                    <tr>
                                        <td>{{ $transport->user->name }}</td>
                                        <td>{{ $transport->collection->waste_type ?? 'N/A' }}</td>
                                        <td>{{ $transport->vehicle->name ?? 'Not Assigned' }}</td>
                                        <td>{{ $transport->driver->name ?? 'Not Assigned' }}</td>
                                        <td>{{ $transport->destination->name ?? 'N/A' }}</td>
                                        <td>{{ $transport->estimated_departure }}</td>
                                        <td>
                                            <a href="{{ route('admin.admin.transportation.start', $transport->id) }}" 
                                               class="btn btn-custom btn-sm">
                                                <i class="fas fa-play me-1"></i>Start
                                            </a>
                                            <a href="{{ route('admin.admin.transportation.details', $transport->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>Details
                                            </a>
                    </td>
                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-clock fa-3x mb-3"></i>
                            <p>No scheduled transports at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Completed Transports -->
        <div class="row">
            <div class="col-12">
                <div class="content-card p-4">
                    <h3 class="text-success fw-bold mb-3">
                        <i class="fas fa-check-circle me-2"></i>Recent Completed Transports
                    </h3>
                    @if($completedTransports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th>User</th>
                                        <th>Waste Type</th>
                                        <th>Vehicle</th>
                                        <th>Driver</th>
                                        <th>Destination</th>
                                        <th>Completed Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedTransports as $transport)
                                    <tr>
                                        <td>{{ $transport->user->name }}</td>
                                        <td>{{ $transport->collection->waste_type ?? 'N/A' }}</td>
                                        <td>{{ $transport->vehicle->name ?? 'N/A' }}</td>
                                        <td>{{ $transport->driver->name ?? 'N/A' }}</td>
                                        <td>{{ $transport->destination->name ?? 'N/A' }}</td>
                                        <td>{{ $transport->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    @endforeach
            </tbody>
        </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <p>No completed transports to show.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Location Update Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Vehicle Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="locationForm">
                        <div class="mb-3">
                            <label for="current_location" class="form-label">Current Location</label>
                            <input type="text" class="form-control" id="current_location" name="current_location" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" step="any" class="form-control" id="latitude" name="latitude" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" step="any" class="form-control" id="longitude" name="longitude" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-custom" onclick="submitLocation()">Update Location</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentTransportId = null;
        
        function updateLocation(transportId) {
            currentTransportId = transportId;
            $('#locationModal').modal('show');
        }
        
        function submitLocation() {
            const formData = new FormData(document.getElementById('locationForm'));
            
            fetch(`/admin/admin/transportation/location/${currentTransportId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    current_location: formData.get('current_location'),
                    latitude: formData.get('latitude'),
                    longitude: formData.get('longitude')
                })
            })
            .then(response => response.json())
            .then(data => {
                alert('Location updated successfully!');
                $('#locationModal').modal('hide');
                location.reload();
            })
            .catch(error => {
                alert('Error updating location');
            });
        }
    </script>
</body>
</html>
