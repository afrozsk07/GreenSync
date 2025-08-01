<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard - GreenSync</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: #f8f9fa; 
        }
        .navbar-brand { 
            font-weight: bold; 
            color: #27ae60; 
        }
        .nav-link { 
            color: #555; 
            font-weight: 500; 
        }
        .nav-link:hover, .nav-link.active { 
            color: #27ae60; 
            font-weight: bold; 
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
        .hero h1 { 
            font-size: 2.5rem; 
            margin-bottom: 20px; 
        }
        .hero p { 
            font-size: 1.2rem; 
            opacity: 0.9; 
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
        .stats-icon {
            font-size: 2.5rem;
            color: #27ae60;
        }
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50;
        }
        .stats-label {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .action-btn {
            background: linear-gradient(135deg, #27AE60, #2ECC71);
            border: none;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            transition: all 0.3s ease;
        }
        .action-btn:hover {
            background: linear-gradient(135deg, #2ECC71, #27AE60);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .recent-activity {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        .status-in-progress { background: #ffeaa7; color: #d63031; }
        .environmental-impact {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            border-radius: 15px;
            padding: 25px;
        }
        .impact-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .impact-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#">GreenSync</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('profile') }}">
                        Welcome, <strong>{{ $user->name }}</strong>!
          </a>
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
    <h1>Welcome to Your GreenSync Dashboard 🌱</h1>
        <p>Track your waste management activities and environmental impact</p>
  </div>
</section>

<!-- Statistics Section -->
<section class="section">
  <div class="container">
        <h2 class="text-center mb-5">Your Waste Management Statistics</h2>
        <div class="row g-4">
      <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="bi bi-recycle stats-icon"></i>
                    <div class="stats-number">{{ $stats['total_requests'] }}</div>
                    <div class="stats-label">Total Requests</div>
                </div>
      </div>
      <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="bi bi-check-circle stats-icon"></i>
                    <div class="stats-number">{{ $stats['completed_collections'] }}</div>
                    <div class="stats-label">Completed Collections</div>
                </div>
      </div>
      <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="bi bi-bag-check stats-icon"></i>
                    <div class="stats-number">{{ number_format($stats['total_waste_collected'], 1) }} kg</div>
                    <div class="stats-label">Total Waste Collected</div>
                </div>
      </div>
      <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="bi bi-clock stats-icon"></i>
                    <div class="stats-number">{{ $pendingRequests }}</div>
                    <div class="stats-label">Pending Requests</div>
                </div>
      </div>
    </div>
  </div>
</section>

<!-- Quick Actions Section -->
<section class="section" style="padding-top: 0;">
    <div class="container">
        <div class="quick-actions">
            <h3 class="text-center mb-4">Quick Actions</h3>
            <div class="row text-center justify-content-center">
                <div class="col-md-4">
                    <a href="{{ route('collections.request.form') }}" class="action-btn">
                        <i class="bi bi-recycle me-2"></i>
                        Request Collection
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('collections') }}" class="action-btn">
                        <i class="bi bi-list-check me-2"></i>
                        View Collections
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('segregation') }}" class="action-btn">
                        <i class="bi bi-filter-circle me-2"></i>
                        Waste Segregation
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Environmental Impact Section -->
<section class="section" style="padding-top: 0;">
    <div class="container">
        <div class="environmental-impact">
            <h3 class="text-center mb-4">Environmental Impact</h3>
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="impact-number">{{ number_format($stats['environmental_impact']['recycled_waste_kg'], 1) }}</div>
                    <div class="impact-label">kg Recycled</div>
                </div>
                <div class="col-md-3">
                    <div class="impact-number">{{ number_format($stats['environmental_impact']['co2_saved_kg'], 1) }}</div>
                    <div class="impact-label">kg CO₂ Saved</div>
                </div>
                <div class="col-md-3">
                    <div class="impact-number">{{ $stats['environmental_impact']['trees_equivalent'] }}</div>
                    <div class="impact-label">Trees Equivalent</div>
                </div>
                <div class="col-md-3">
                    <div class="impact-number">{{ number_format($stats['segregation_accuracy'], 1) }}%</div>
                    <div class="impact-label">Segregation Accuracy</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Activities Section -->
<section class="section" style="padding-top: 0;">
  <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="recent-activity">
                    <h4 class="mb-4">Recent Collection Requests</h4>
                    @if($recentRequests->count() > 0)
                        @foreach($recentRequests as $request)
                            <div class="activity-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $request->waste_type }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $request->quantity }} kg • {{ $request->pickup_date }}</small>
                                    </div>
                                    <span class="status-badge status-{{ $request->status }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No recent requests</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="recent-activity">
                    <h4 class="mb-4">Recent Collections</h4>
                    @if($recentCollections->count() > 0)
                        @foreach($recentCollections as $collection)
                            <div class="activity-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $collection->waste_type }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $collection->quantity }} kg • {{ $collection->pickup_date }}</small>
                                    </div>
                                    <span class="status-badge status-{{ $collection->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $collection->status)) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No recent collections</p>
                    @endif
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
