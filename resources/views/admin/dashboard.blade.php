<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GreenSync</title>
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
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            backdrop-filter: blur(10px);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        
        .welcome-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .btn-custom {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
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
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
        }
        
        .stats-label {
            color: #6c757d;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
  <div class="container">
            <a class="navbar-brand fw-bold" href="#">
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
        <!-- Welcome Section -->
        <div class="welcome-section text-center">
            <h1 class="display-4 fw-bold text-primary mb-3">
                <i class="fas fa-chart-line me-3"></i>Admin Dashboard
            </h1>
            <p class="lead text-muted">Manage and monitor all environmental management operations</p>
        </div>

        <!-- Statistics Row -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number">150</div>
                        <div class="stats-label">Total Collections</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number">45</div>
                        <div class="stats-label">Active Transport</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number">89</div>
        
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number">12</div>
                        <div class="stats-label">Pending Tasks</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card feature-card text-center">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-recycle"></i>
                        </div>
                        <h5 class="card-title fw-bold">Waste Collection</h5>
                        <p class="card-text text-muted">Schedule and manage waste pickups for households and businesses.</p>
                        <a href="{{ route('admin.admin.collections') }}" class="btn btn-custom">
                            <i class="fas fa-cog me-2"></i>Manage Collections
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card feature-card text-center">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h5 class="card-title fw-bold">Transportation</h5>
                        <p class="card-text text-muted">Track waste transportation and logistics in real-time.</p>
                        <a href="{{ route('admin.admin.transportation') }}" class="btn btn-custom">
                            <i class="fas fa-route me-2"></i>Manage Transport
                        </a>
                    </div>
      </div>
      </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card feature-card text-center">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-trash-alt"></i>
      </div>
                        
      </div>
    </div>
  </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="card feature-card text-center">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-filter"></i>
                        </div>
                        <h5 class="card-title fw-bold">Waste Segregation</h5>
                        <p class="card-text text-muted">Monitor and manage segregation of different waste types.</p>
                        <a href="{{ route('admin.admin.segregation') }}" class="btn btn-custom">
                            <i class="fas fa-sort me-2"></i>Manage Segregation
                        </a>
                    </div>
                </div>
            </div>
        </div>

<!-- About Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card feature-card">
                    <div class="card-body">
                        <h3 class="text-primary fw-bold mb-4">
                            <i class="fas fa-leaf me-3"></i>About GreenSync
                        </h3>
                        <p class="lead text-muted">
                            At GreenSync, we believe in sustainable environmental management. We empower individuals and businesses 
                            to manage their waste in a responsible, eco-friendly manner. Our platform simplifies waste 
                            collection, transportation, and segregation tracking — promoting a greener planet for all.
                        </p>
                        <div class="row mt-4">
                            <div class="col-md-4 text-center">
                                <i class="fas fa-globe-americas text-success" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Global Impact</h6>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="fas fa-shield-alt text-primary" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Safe & Secure</h6>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="fas fa-chart-line text-info" style="font-size: 2rem;"></i>
                                <h6 class="mt-2">Real-time Tracking</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  </div>

<!-- Footer -->
    <footer class="text-center py-4 mt-5">
        <div class="container">
            <p class="text-white mb-0">
                <i class="fas fa-heart text-danger me-2"></i>
                © 2025 GreenSync. All Rights Reserved.
            </p>
        </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
