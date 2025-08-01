<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transportation Tracking - GreenSync</title>
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
        .hero { 
            background: linear-gradient(135deg, #27AE60, #2ECC71); 
            color: white;
            text-align: center;
            padding: 60px 20px;
        }
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .transport-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-left: 4px solid #27ae60;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-scheduled { background: #cce5ff; color: #004085; }
        .status-in-transit { background: #ffeaa7; color: #d63031; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        .status-cancelled { background: #e2e3e5; color: #383d41; }
        .btn-primary {
            background: linear-gradient(135deg, #27AE60, #2ECC71);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2ECC71, #27AE60);
        }
        .nav-tabs .nav-link {
            color: #27ae60;
            border: none;
            border-bottom: 2px solid transparent;
        }
        .nav-tabs .nav-link.active {
            color: #27ae60;
            border-bottom: 2px solid #27ae60;
            background: none;
        }
        .tracking-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
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
                    <a class="nav-link active" href="{{ route('transportation') }}">Transportation</a>
                </li>
                                <li class="nav-item">
                    <a class="nav-link" href="{{ route('segregation') }}">Segregation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile') }}">Profile</a>
                </li>
 
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-success ms-2" type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Transportation Tracking</h1>
        <p>Track your waste transportation in real-time</p>
    </div>
</section>

<!-- Statistics Section -->
<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="bi bi-truck text-primary" style="font-size: 2rem;"></i>
                    <div class="fw-bold fs-4">{{ $stats['total_transports'] }}</div>
                    <small class="text-muted">Total Transports</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="bi bi-arrow-repeat text-warning" style="font-size: 2rem;"></i>
                    <div class="fw-bold fs-4">{{ $stats['active_transports'] }}</div>
                    <small class="text-muted">Active Transports</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    <div class="fw-bold fs-4">{{ $stats['completed_transports'] }}</div>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="bi bi-clock text-info" style="font-size: 2rem;"></i>
                    <div class="fw-bold fs-4">{{ $stats['scheduled_transports'] }}</div>
                    <small class="text-muted">Scheduled</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="section" style="padding-top: 0;">
    <div class="container">
        @if($collections->count() > 0)
            <ul class="nav nav-tabs" id="transportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                        All Transports ({{ $collections->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                        Active ({{ $activeTransports->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">
                        Completed ({{ $completedTransports->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="scheduled-tab" data-bs-toggle="tab" data-bs-target="#scheduled" type="button" role="tab">
                        Scheduled ({{ $scheduledTransports->count() }})
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="transportTabsContent">
                <!-- All Transports -->
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    @foreach($collections as $collection)
                        <div class="transport-card">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="mb-2">{{ $collection->waste_type }}</h5>
                                    <div class="tracking-info">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="bi bi-scale-fill"></i> {{ $collection->quantity }} kg
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar"></i> {{ $collection->pickup_date }}
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="bi bi-truck"></i> 
                                                    {{ $collection->vehicle ? $collection->vehicle->vehicle_number : 'Not assigned' }}
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-person"></i> 
                                                    {{ $collection->driver ? $collection->driver->name : 'Not assigned' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    @if($collection->transportation)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <strong>Current Location:</strong> {{ $collection->transportation->current_location ?? 'In transit' }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 text-end">
                                    @if($collection->transportation)
                                        <span class="status-badge status-{{ $collection->transportation->status }}">
                                            {{ ucfirst(str_replace('_', ' ', $collection->transportation->status)) }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $collection->created_at->format('M d, Y') }}</small>
                                        <br>
                                        <a href="{{ route('transportation.track', $collection->id) }}" 
                                           class="btn btn-sm btn-primary mt-2">
                                            Track
                                        </a>
                                    @else
                                        <span class="status-badge status-scheduled">No Transport</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Active Transports -->
                <div class="tab-pane fade" id="active" role="tabpanel">
                    @if($activeTransports->count() > 0)
                        @foreach($activeTransports as $collection)
                            <div class="transport-card">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-2">{{ $collection->waste_type }}</h5>
                                        <div class="tracking-info">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="bi bi-scale-fill"></i> {{ $collection->quantity }} kg
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar"></i> {{ $collection->pickup_date }}
                                                    </small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="bi bi-truck"></i> 
                                                        {{ $collection->vehicle ? $collection->vehicle->vehicle_number : 'Not assigned' }}
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="bi bi-person"></i> 
                                                        {{ $collection->driver ? $collection->driver->name : 'Not assigned' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        @if($collection->transportation)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Current Location:</strong> {{ $collection->transportation->current_location ?? 'In transit' }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="status-badge status-in-transit">
                                            In Transit
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $collection->created_at->format('M d, Y') }}</small>
                                        <br>
                                        <a href="{{ route('transportation.track', $collection->id) }}" 
                                           class="btn btn-sm btn-primary mt-2">
                                            Track Live
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-truck text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No active transports</p>
                        </div>
                    @endif
                </div>
                
                <!-- Completed Transports -->
                <div class="tab-pane fade" id="completed" role="tabpanel">
                    @if($completedTransports->count() > 0)
                        @foreach($completedTransports as $collection)
                            <div class="transport-card">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-2">{{ $collection->waste_type }}</h5>
                                        <div class="tracking-info">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="bi bi-scale-fill"></i> {{ $collection->quantity }} kg
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar"></i> {{ $collection->pickup_date }}
                                                    </small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="bi bi-truck"></i> 
                                                        {{ $collection->vehicle ? $collection->vehicle->vehicle_number : 'Not assigned' }}
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="bi bi-person"></i> 
                                                        {{ $collection->driver ? $collection->driver->name : 'Not assigned' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        @if($collection->transportation)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $collection->transportation->status)) }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="status-badge status-completed">
                                            Completed
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $collection->created_at->format('M d, Y') }}</small>
                                        <br>
                                        <a href="{{ route('transportation.track', $collection->id) }}" 
                                           class="btn btn-sm btn-outline-primary mt-2">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No completed transports</p>
                        </div>
                    @endif
                </div>
                
                <!-- Scheduled Transports -->
                <div class="tab-pane fade" id="scheduled" role="tabpanel">
                    @if($scheduledTransports->count() > 0)
                        @foreach($scheduledTransports as $collection)
                            <div class="transport-card">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-2">{{ $collection->waste_type }}</h5>
                                        <div class="tracking-info">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="bi bi-scale-fill"></i> {{ $collection->quantity }} kg
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar"></i> {{ $collection->pickup_date }}
                                                    </small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="bi bi-truck"></i> 
                                                        {{ $collection->vehicle ? $collection->vehicle->vehicle_number : 'Not assigned' }}
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="bi bi-person"></i> 
                                                        {{ $collection->driver ? $collection->driver->name : 'Not assigned' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        @if($collection->transportation)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $collection->transportation->status)) }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="status-badge status-scheduled">
                                            Scheduled
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $collection->created_at->format('M d, Y') }}</small>
                                        <br>
                                        <a href="{{ route('transportation.track', $collection->id) }}" 
                                           class="btn btn-sm btn-outline-primary mt-2">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clock text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No scheduled transports</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-truck text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Transportation Records</h4>
                <p class="text-muted">Your waste collections will appear here once they are assigned for transportation.</p>
                <a href="{{ route('collections') }}" class="btn btn-primary">
                    Request Collection
                </a>
            </div>
        @endif
        
        <div class="text-center mt-4">
            <a href="{{ route('transportation.history') }}" class="btn btn-outline-primary">
                View Full History
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-success text-white text-center py-4 mt-5">
    <p class="mb-0">© 2025 GreenSync. All Rights Reserved.</p>
</footer>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Auto-refresh active transports every 30 seconds
    setInterval(function() {
        if (document.querySelector('#active-tab').classList.contains('active')) {
            location.reload();
        }
    }, 30000);
</script>

</body>
</html>
