<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GreenSync - Environmental Management Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    header {
      background: rgba(255, 255, 255, 0.95);
      color: #27ae60;
      text-align: center;
      padding: 2rem;
      backdrop-filter: blur(10px);
    }
    .hero {
      text-align: center;
      color: #2c3e50;
      padding: 4rem 0;
    }
    .hero h1 {
      font-size: 3rem;
      margin-bottom: 1rem;
    }
    .hero .lead {
      font-size: 1.5rem;
      margin-bottom: 2rem;
    }
    .features {
      padding: 50px 0;
    }
    .feature {
      text-align: center;
      padding: 20px;
    }
    .feature i {
      font-size: 3rem;
      color: #27ae60;
    }
    .feature h4 {
      color: #2c3e50;
      margin-top: 1rem;
    }
    .feature p {
      color: #6c757d;
    }
    footer {
      background: white;
      color: black;
      text-align: center;
      padding: 15px;
      margin-top: auto;
      border-top: 1px solid #e9ecef;
    }
    .btn-primary {
      background: #27ae60;
      border: none;
      color: white;
    }
    .btn-primary:hover {
      background: #f8f9fa;
      color: #27ae60;
      border: 1px solid #27ae60;
    }
    .btn-outline-white {
      color: #27ae60;
      border-color: #27ae60;
      background: #f8f9fa;
    }
    .btn-outline-white:hover {
      color: white;
      background: #27ae60;
      border-color: #27ae60;
    }
    
    /* Enhanced Login Modal Styles */
    .login-option-card {
      background: #f8f9fa;
      border: 2px solid #e9ecef;
      border-radius: 12px;
      padding: 20px;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-bottom: 10px;
    }
    
    .login-option-card:hover {
      background: #e9ecef;
      border-color: #27ae60;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .login-option-icon {
      font-size: 2.5rem;
      color: #27ae60;
      margin-bottom: 15px;
    }
    
    .login-option-title {
      color: #2c3e50;
      font-weight: 600;
      margin-bottom: 8px;
    }
    
    .login-option-desc {
      color: #6c757d;
      font-size: 0.9rem;
      margin-bottom: 0;
    }
    
    .modal-content {
      border-radius: 15px;
      border: none;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .modal-header {
      border-radius: 15px 15px 0 0;
      border-bottom: none;
    }
    
    .btn-close-white {
      filter: invert(1) grayscale(100%) brightness(200%);
    }
  </style>
</head>
<body>

<header>
  <h1>GreenSync Environmental Management Platform</h1>
                  <p>Efficient Waste Collection, Transportation &amp; Segregation</p>
</header>

<div class="container hero">
  <h1>Welcome to GreenSync! 🌱</h1>
  <p class="lead">Making the world cleaner, greener and healthier — one step at a time.</p>
  <div class="mt-4">
    <!-- Trigger modal for login -->
    <button class="btn btn-primary btn-lg me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
    <a href="{{ route('register') }}" class="btn btn-outline-white btn-lg">Register</a>
  </div>
</div>

<!-- Features Section -->
<section class="features container">
  <div class="row">
    <div class="col-md-3 feature">
      <i class="bi bi-trash-fill"></i>
      <h4 class="mt-3">Collection</h4>
      <p>Schedule waste pickups easily from your location.</p>
    </div>
    <div class="col-md-3 feature">
      <i class="bi bi-truck"></i>
      <h4 class="mt-3">Transportation</h4>
      <p>Eco-friendly transportation of waste across zones.</p>
    </div>
    <div class="col-md-3 feature">
      <i class="bi bi-funnel-fill"></i>
      <h4 class="mt-3">Segregation</h4>
      <p>Segregate waste efficiently for better recycling.</p>
    </div>
    <div class="col-md-3 feature">
      <i class="bi bi-recycle"></i>
      <h4 class="mt-3">Disposal</h4>
      <p>Safe and environmentally friendly waste disposal.</p>
    </div>
  </div>
</section>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="loginModalLabel">
          <i class="bi bi-person-circle me-2"></i>Choose Login Type
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <div class="row g-3">
          <div class="col-12">
            <div class="login-option-card" onclick="window.location.href='{{ route('admin.admin-login') }}'">
              <div class="login-option-icon">
                <i class="bi bi-shield-check"></i>
              </div>
              <h6 class="login-option-title">Administrator</h6>
              <p class="login-option-desc">Manage waste operations, track vehicles, and oversee collections</p>
            </div>
          </div>
          <div class="col-12">
            <div class="login-option-card" onclick="window.location.href='{{ route('login') }}'">
              <div class="login-option-icon">
                <i class="bi bi-person"></i>
              </div>
              <h6 class="login-option-title">User</h6>
              <p class="login-option-desc">Request collections, track waste, and manage your profile</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<footer>
  <p>© 2025 GreenSync. All Rights Reserved.</p>
</footer>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
</body>
</html>
