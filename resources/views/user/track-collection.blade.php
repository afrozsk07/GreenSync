<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Collection - GreenSync</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif; 
            background: #f8f9fa; 
        }
        .navbar-brand { 
            font-weight: bold; 
            color: #27ae60; 
        }
        .section { 
            padding: 80px 20px; 
        }
        .tracking-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-left: 30px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 5px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #27ae60;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #27ae60;
        }
        .timeline-item.completed::before {
            background: #27ae60;
            box-shadow: 0 0 0 3px #27ae60;
        }
        .timeline-item.pending::before {
            background: #ffc107;
            box-shadow: 0 0 0 3px #ffc107;
        }
        .timeline-item.current::before {
            background: #007bff;
            box-shadow: 0 0 0 3px #007bff;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d1ecf1; color: #0c5460; }
        .status-in-progress { background: #ffeaa7; color: #d63031; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-scheduled { background: #cce5ff; color: #004085; }
        .status-in-transit { background: #ffeaa7; color: #d63031; }
        .btn-primary {
            background: linear-gradient(135deg, #27AE60, #2ECC71);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2ECC71, #27AE60);
        }
        .info-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .progress-bar {
            background: linear-gradient(135deg, #27AE60, #2ECC71);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">GreenSync</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('collections') }}">Collections</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('segregation') }}">Segregation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile') }}">Profile</a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">
                        <i class="bi bi-truck text-primary"></i>
                        Track Collection #{{ $collection->id }}
                    </h2>
                    <a href="{{ route('collections') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Collections
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Collection Information -->
            <div class="col-lg-4">
                <div class="tracking-card">
                    <h5 class="mb-3">
                        <i class="bi bi-info-circle text-primary"></i>
                        Collection Details
                    </h5>
                    
                    <div class="info-card">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Waste Type</small>
                                <p class="mb-0 fw-bold">{{ $trackingInfo['waste_type'] }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Quantity</small>
                                <p class="mb-0 fw-bold">{{ $trackingInfo['quantity'] }} kg</p>
                            </div>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Pickup Date</small>
                                <p class="mb-0 fw-bold">{{ $trackingInfo['pickup_date'] }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Pickup Time</small>
                                <p class="mb-0 fw-bold">{{ $trackingInfo['pickup_time'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="info-card">
                        <small class="text-muted">Pickup Address</small>
                        <p class="mb-0 fw-bold">{{ $trackingInfo['address'] }}</p>
                    </div>

                    @if($trackingInfo['collection_notes'])
                    <div class="info-card">
                        <small class="text-muted">Notes</small>
                        <p class="mb-0">{{ $trackingInfo['collection_notes'] }}</p>
                    </div>
                    @endif
                </div>

                <!-- Vehicle & Driver Info -->
                <div class="tracking-card">
                    <h5 class="mb-3">
                        <i class="bi bi-truck text-primary"></i>
                        Vehicle & Driver
                    </h5>
                    
                    <div class="info-card">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Vehicle</small>
                                <p class="mb-0 fw-bold">{{ $trackingInfo['vehicle_info'] }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Driver</small>
                                <p class="mb-0 fw-bold">{{ $trackingInfo['driver_info'] }}</p>
                            </div>
                        </div>
                    </div>

                    @if($trackingInfo['transport_status'])
                    <div class="info-card">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Transport Status</small>
                                <span class="status-badge status-{{ str_replace('_', '-', $trackingInfo['transport_status']) }}">
                                    {{ ucfirst(str_replace('_', ' ', $trackingInfo['transport_status'])) }}
                                </span>
                            </div>
                            @if($trackingInfo['current_location'])
                            <div class="col-6">
                                <small class="text-muted">Current Location</small>
                                <p class="mb-0 fw-bold">{{ $trackingInfo['current_location'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="col-lg-8">
                <div class="tracking-card">
                    <h5 class="mb-4">
                        <i class="bi bi-clock-history text-primary"></i>
                        Tracking Timeline
                    </h5>
                    
                    <div class="timeline">
                        <!-- Request Submitted -->
                        <div class="timeline-item completed">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Request Submitted</h6>
                                    <p class="text-muted mb-2">{{ $collection->created_at->format('M d, Y H:i') }}</p>
                                    <p class="mb-0">Your waste collection request has been submitted and is under review.</p>
                                </div>
                                <span class="status-badge status-completed">Completed</span>
                            </div>
                        </div>

                        <!-- Request Approved -->
                        @if($collection->request && $collection->request->status === 'approved')
                        <div class="timeline-item completed">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Request Approved</h6>
                                    <p class="text-muted mb-2">{{ $collection->request->updated_at->format('M d, Y H:i') }}</p>
                                    <p class="mb-0">Your request has been approved and scheduled for collection.</p>
                                </div>
                                <span class="status-badge status-completed">Completed</span>
                            </div>
                        </div>
                        @endif

                        <!-- Vehicle Assigned -->
                        @if($trackingInfo['vehicle_info'] !== 'Not assigned')
                        <div class="timeline-item completed">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Vehicle Assigned</h6>
                                    <p class="text-muted mb-2">Vehicle {{ $trackingInfo['vehicle_info'] }} assigned</p>
                                    <p class="mb-0">A vehicle has been assigned to your collection.</p>
                                </div>
                                <span class="status-badge status-completed">Completed</span>
                            </div>
                        </div>
                        @endif

                        <!-- Collection Started -->
                        @if($trackingInfo['actual_pickup_time'])
                        <div class="timeline-item completed">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Collection Started</h6>
                                    <p class="text-muted mb-2">{{ \Carbon\Carbon::parse($trackingInfo['actual_pickup_time'])->format('M d, Y H:i') }}</p>
                                    <p class="mb-0">The collection team has started picking up your waste.</p>
                                </div>
                                <span class="status-badge status-completed">Completed</span>
                            </div>
                        </div>
                        @endif

                        <!-- Transportation Started -->
                        @if($trackingInfo['actual_departure'])
                        <div class="timeline-item completed">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Transportation Started</h6>
                                    <p class="text-muted mb-2">{{ \Carbon\Carbon::parse($trackingInfo['actual_departure'])->format('M d, Y H:i') }}</p>
                                    <p class="mb-0">Your waste is being transported to the processing center.</p>
                                </div>
                                <span class="status-badge status-completed">Completed</span>
                            </div>
                        </div>
                        @endif

                        <!-- In Transit -->
                        @if($trackingInfo['transport_status'] === 'in_transit')
                        <div class="timeline-item current">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">In Transit</h6>
                                    <p class="text-muted mb-2">Currently in progress</p>
                                    <p class="mb-0">Your waste is being transported to the destination.</p>
                                </div>
                                <span class="status-badge status-in-transit">In Progress</span>
                            </div>
                        </div>
                        @endif

                        <!-- Transportation Completed -->
                        @if($trackingInfo['actual_arrival'])
                        <div class="timeline-item completed">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Transportation Completed</h6>
                                    <p class="text-muted mb-2">{{ \Carbon\Carbon::parse($trackingInfo['actual_arrival'])->format('M d, Y H:i') }}</p>
                                    <p class="mb-0">Your waste has been delivered to the processing center.</p>
                                </div>
                                <span class="status-badge status-completed">Completed</span>
                            </div>
                        </div>
                        @endif

                        <!-- Collection Completed -->
                        @if($trackingInfo['completion_time'])
                        <div class="timeline-item completed">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Collection Completed</h6>
                                    <p class="text-muted mb-2">{{ \Carbon\Carbon::parse($trackingInfo['completion_time'])->format('M d, Y H:i') }}</p>
                                    <p class="mb-0">Your waste collection has been completed successfully.</p>
                                </div>
                                <span class="status-badge status-completed">Completed</span>
                            </div>
                        </div>
                        @endif

                        <!-- Next Steps -->
                        @if($collection->status === 'scheduled' && !$trackingInfo['actual_pickup_time'])
                        <div class="timeline-item current">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Awaiting Collection</h6>
                                    <p class="text-muted mb-2">Scheduled for {{ $trackingInfo['pickup_date'] }}</p>
                                    <p class="mb-0">Your collection is scheduled. The team will arrive at the specified time.</p>
                                </div>
                                <span class="status-badge status-pending">Pending</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 