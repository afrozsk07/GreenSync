<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collections - GreenSync</title>
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
        .status-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-cancelled { background: #e2e3e5; color: #383d41; }
        .status-scheduled { background: #cce5ff; color: #004085; }
        .status-assigned { background: #fff3cd; color: #856404; }
        .status-in-progress { background: #ffeaa7; color: #d63031; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
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
        .collection-item {
            border-left: 4px solid #27ae60;
            padding: 15px;
            margin-bottom: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
                    <a class="nav-link active" href="{{ route('collections') }}">Collections</a>
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
        <h1>Collection Status Tracking</h1>
        <p>Track your waste collection requests and active collections</p>
    </div>
</section>

<!-- Main Content -->
<section class="section" style="padding-top: 0;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Your Collections</h3>
                </div>
                
                <ul class="nav nav-tabs" id="collectionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                            Pending Requests ({{ $pendingRequests->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                            Active Collections ({{ $activeCollections->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">
                            Completed ({{ $completedCollections->count() }})
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="collectionTabsContent">
                    <!-- Pending Requests -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        @if($pendingRequests->count() > 0)
                            @foreach($pendingRequests as $request)
                                <div class="collection-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $request->waste_type }}</h6>
                                            <p class="mb-1 text-muted">
                                                <i class="bi bi-scale-fill"></i> {{ $request->quantity }} kg
                                                <i class="bi bi-calendar ms-3"></i> {{ $request->pickup_date }}
                                                <i class="bi bi-clock ms-3"></i> {{ $request->pickup_time }}
                                            </p>
                                            <small class="text-muted">{{ $request->address }}</small>
                                            @if($request->description)
                                                <br><small class="text-muted">{{ $request->description }}</small>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <span class="status-badge status-{{ $request->status }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $request->created_at->format('M d, Y') }}</small>
                                            <br>
                                            <a href="{{ route('collections.cancel', $request->id) }}" 
                                               class="btn btn-sm btn-outline-danger mt-2"
                                               onclick="return confirm('Are you sure you want to cancel this request?')">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-clock text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">No pending requests</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Active Collections -->
                    <div class="tab-pane fade" id="active" role="tabpanel">
                        @if($activeCollections->count() > 0)
                            @foreach($activeCollections as $collection)
                                <div class="collection-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $collection->waste_type }}</h6>
                                            <p class="mb-1 text-muted">
                                                <i class="bi bi-scale-fill"></i> {{ $collection->quantity }} kg
                                                <i class="bi bi-calendar ms-3"></i> {{ $collection->pickup_date }}
                                                <i class="bi bi-clock ms-3"></i> {{ $collection->pickup_time }}
                                            </p>
                                            <small class="text-muted">{{ $collection->address }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="status-badge status-{{ $collection->status }}">
                                                {{ ucfirst(str_replace('_', ' ', $collection->status)) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $collection->created_at->format('M d, Y') }}</small>
                                            <br>
                                            <a href="{{ route('collections.track', $collection->id) }}" 
                                               class="btn btn-sm btn-primary mt-2">
                                                Track
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-truck text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">No active collections</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Completed Collections -->
                    <div class="tab-pane fade" id="completed" role="tabpanel">
                        @if($completedCollections->count() > 0)
                            @foreach($completedCollections->take(10) as $collection)
                                <div class="collection-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $collection->waste_type }}</h6>
                                            <p class="mb-1 text-muted">
                                                <i class="bi bi-scale-fill"></i> {{ $collection->quantity }} kg
                                                <i class="bi bi-calendar ms-3"></i> {{ $collection->pickup_date }}
                                                <i class="bi bi-check-circle ms-3"></i> Completed
                                            </p>
                                            <small class="text-muted">{{ $collection->address }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="status-badge status-{{ $collection->status }}">
                                                {{ ucfirst($collection->status) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $collection->completion_time ? $collection->completion_time->format('M d, Y') : $collection->updated_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-check-circle text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">No completed collections</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Completed Collections -->
                    <div class="tab-pane fade" id="completed" role="tabpanel">
                        @if($completedCollections->count() > 0)
                            @foreach($completedCollections->take(5) as $collection)
                                <div class="collection-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $collection->waste_type }}</h6>
                                            <p class="mb-1 text-muted">
                                                <i class="bi bi-scale-fill"></i> {{ $collection->quantity }} kg
                                                <i class="bi bi-calendar ms-3"></i> {{ $collection->pickup_date }}
                                                <i class="bi bi-check-circle ms-3"></i> Completed
                                            </p>
                                            <small class="text-muted">{{ $collection->address }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="status-badge status-{{ $collection->status }}">
                                                {{ ucfirst($collection->status) }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $collection->completion_time ? $collection->completion_time->format('M d, Y') : $collection->updated_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($completedCollections->count() > 5)
                                <div class="text-center mt-4">
                                    <a href="{{ route('collections.history') }}" class="btn btn-outline-primary">
                                        View Full History
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-check-circle text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">No completed collections</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-success text-white text-center py-4 mt-5">
    <p class="mb-0">© 2025 GreenSync. All Rights Reserved.</p>
</footer>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
