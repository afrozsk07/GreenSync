<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Management - GreenSync Admin</title>
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
        
        .btn-danger-custom {
            background: linear-gradient(45deg, #dc3545, #c82333);
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
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-in-progress { background: #cce5ff; color: #004085; }
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
                        <i class="fas fa-recycle me-3"></i>Collection Management
                    </h1>
                    <p class="text-muted">Manage waste collection requests and track collection progress</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-primary">{{ $stats['total_requests'] }}</div>
                        <div class="stats-label">Total Requests</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-warning">{{ $stats['pending_requests'] }}</div>
                        <div class="stats-label">Pending Requests</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-info">{{ $stats['active_collections'] }}</div>
                        <div class="stats-label">Active Collections</div>
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

        <!-- Pending Requests -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-card p-4">
                    <h3 class="text-warning fw-bold mb-3">
                        <i class="fas fa-clock me-2"></i>Pending Requests
                    </h3>
                    @if($pendingRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-warning">
                <tr>
                                        <th>User</th>
                                        <th>Waste Type</th>
                                        <th>Quantity</th>
                                        <th>Pickup Date</th>
                                        <th>Address</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingRequests as $request)
                                    <tr>
                                        <td>{{ $request->user->name }}</td>
                                        <td>{{ $request->waste_type }}</td>
                                        <td>{{ $request->quantity }} kg</td>
                                        <td>{{ $request->pickup_date }}</td>
                                        <td>{{ $request->address }}</td>
                                        <td>
                                            <a href="{{ route('admin.admin.collections.approve', $request->id) }}" 
                                               class="btn btn-success-custom btn-sm">
                                                <i class="fas fa-check me-1"></i>Approve
                                            </a>
                                            <a href="{{ route('admin.admin.collections.reject', $request->id) }}" 
                                               class="btn btn-danger-custom btn-sm">
                                                <i class="fas fa-times me-1"></i>Reject
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No pending requests at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Active Collections -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-card p-4">
                    <h3 class="text-info fw-bold mb-3">
                        <i class="fas fa-truck me-2"></i>Active Collections
                    </h3>
                    @if($activeCollections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-info">
                                    <tr>
                                        <th>User</th>
                    <th>Waste Type</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Vehicle</th>
                                        <th>Driver</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                                    @foreach($activeCollections as $collection)
                                    <tr>
                                        <td>{{ $collection->user->name }}</td>
                                        <td>{{ $collection->waste_type }}</td>
                                        <td>{{ $collection->quantity }} kg</td>
                                        <td>
                                            <span class="status-badge status-{{ str_replace('_', '-', $collection->status) }}">
                                                {{ ucfirst(str_replace('_', ' ', $collection->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $collection->vehicle->name ?? 'Not Assigned' }}</td>
                                        <td>{{ $collection->driver->name ?? 'Not Assigned' }}</td>
                    <td>
                                            @if($collection->status == 'scheduled')
                                                <a href="{{ route('admin.admin.transportation.create', ['collection_id' => $collection->id]) }}" 
                                                   class="btn btn-custom btn-sm">
                                                    <i class="fas fa-play me-1"></i>Start
                                                </a>
                                            @elseif($collection->status == 'in_progress')
                                                <a href="{{ route('admin.admin.collections.complete', $collection->id) }}" 
                                                   class="btn btn-success-custom btn-sm">
                                                    <i class="fas fa-check me-1"></i>Complete
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.admin.collections.details', $collection->id) }}" 
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
                            <p>No active collections at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Completed Collections -->
        <div class="row">
            <div class="col-12">
                <div class="content-card p-4">
                    <h3 class="text-success fw-bold mb-3">
                        <i class="fas fa-check-circle me-2"></i>Recent Completed Collections
                    </h3>
                    @if($completedCollections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th>User</th>
                                        <th>Waste Type</th>
                                        <th>Quantity</th>
                                        <th>Completed Date</th>
                                        <th>Vehicle</th>
                                        <th>Driver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedCollections as $collection)
                                    <tr>
                                        <td>{{ $collection->user->name }}</td>
                                        <td>{{ $collection->waste_type }}</td>
                                        <td>{{ $collection->quantity }} kg</td>
                                        <td>{{ $collection->updated_at->format('M d, Y H:i') }}</td>
                                        <td>{{ $collection->vehicle->name ?? 'N/A' }}</td>
                                        <td>{{ $collection->driver->name ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
            </tbody>
        </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <p>No completed collections to show.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
