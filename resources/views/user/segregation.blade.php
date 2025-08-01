<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Segregation - GreenSync</title>
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
        .segregation-form {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .category-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-left: 4px solid #27ae60;
        }
        .accuracy-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .accuracy-excellent { background: #d4edda; color: #155724; }
        .accuracy-good { background: #cce5ff; color: #004085; }
        .accuracy-fair { background: #fff3cd; color: #856404; }
        .accuracy-poor { background: #f8d7da; color: #721c24; }
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
        .progress-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: conic-gradient(#27ae60 0deg, #27ae60 calc(var(--progress) * 3.6deg), #e9ecef calc(var(--progress) * 3.6deg));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        .progress-circle::before {
            content: attr(data-progress) '%';
            background: white;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #27ae60;
        }
        .educational-section {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .tip-card {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
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
                    <a class="nav-link active" href="{{ route('segregation') }}">Segregation</a>
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
        <h1>Waste Segregation</h1>
        <p>Learn proper waste segregation and track your progress</p>
    </div>
</section>

<!-- Statistics Section -->
<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="bi bi-recycle text-success" style="font-size: 2rem;"></i>
                    <div class="fw-bold fs-4">{{ $stats['total_segregations'] }}</div>
                    <small class="text-muted">Total Segregations</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="bi bi-check-circle text-primary" style="font-size: 2rem;"></i>
                    <div class="fw-bold fs-4">{{ $stats['correct_segregations'] }}</div>
                    <small class="text-muted">Correct Segregations</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <div class="progress-circle" data-progress="{{ $stats['accuracy_percentage'] }}" style="--progress: {{ $stats['accuracy_percentage'] }}"></div>
                    <small class="text-muted mt-2 d-block">Accuracy Rate</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card text-center">
                    <i class="bi bi-scale-fill text-info" style="font-size: 2rem;"></i>
                    <div class="fw-bold fs-4">{{ number_format($stats['total_quantity'], 1) }} kg</div>
                    <small class="text-muted">Total Waste Segregated</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="section" style="padding-top: 0;">
    <div class="container">
        <div class="row">
            <!-- Segregation Form -->
            <div class="col-lg-4">
                <div class="segregation-form">
                    <h4 class="mb-4">Submit Waste Segregation</h4>
                    
                    <form id="segregationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="waste_type" class="form-label">Waste Type *</label>
                            <input type="text" class="form-control" id="waste_type" name="waste_type" 
                                   placeholder="e.g., Plastic bottle, Paper, Food scraps" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Waste Category *</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity (kg) *</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   min="0.1" max="1000" step="0.1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Additional details about the waste"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Submit Segregation</button>
                    </form>
                    
                    <div id="result" class="mt-3" style="display: none;">
                        <div class="alert" id="resultAlert">
                            <div id="resultMessage"></div>
                            <div id="accuracyInfo" class="mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Educational Content and History -->
            <div class="col-lg-8">
                <ul class="nav nav-tabs" id="segregationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="learn-tab" data-bs-toggle="tab" data-bs-target="#learn" type="button" role="tab">
                            Learn Segregation
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                            Your History ({{ $userSegregations->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tips-tab" data-bs-toggle="tab" data-bs-target="#tips" type="button" role="tab">
                            Tips & Guidelines
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="segregationTabsContent">
                    <!-- Learn Segregation -->
                    <div class="tab-pane fade show active" id="learn" role="tabpanel">
                        <div class="educational-section">
                            <h4 class="mb-3">Waste Categories Guide</h4>
                            <div class="row">
                                @foreach($educationalContent['categories'] as $category)
                                    <div class="col-md-6 mb-3">
                                        <div class="category-card">
                                            <h5 class="text-success">{{ $category['name'] }}</h5>
                                            <p class="text-muted">{{ $category['description'] }}</p>
                                            <strong class="text-dark">Examples:</strong>
                                            <ul class="small text-dark">
                                                @foreach($category['examples'] as $example)
                                                    <li>{{ $example }}</li>
                                                @endforeach
                                            </ul>
                                            <strong class="text-dark">Tips:</strong>
                                            <ul class="small text-dark">
                                                @foreach($category['tips'] as $tip)
                                                    <li>{{ $tip }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="educational-section">
                            <h4 class="mb-3">Best Practices</h4>
                            <div class="row">
                                @foreach($educationalContent['best_practices'] as $practice)
                                    <div class="col-md-6 mb-2">
                                        <div class="tip-card">
                                            <i class="bi bi-lightbulb text-warning me-2"></i>
                                            <span class="text-dark">{{ $practice }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- History -->
                    <div class="tab-pane fade" id="history" role="tabpanel">
                        @if($recentSegregations->count() > 0)
                            @foreach($recentSegregations as $segregation)
                                <div class="category-card">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="mb-1">{{ $segregation->waste_type }}</h6>
                                            <p class="mb-1 text-muted">
                                                <i class="bi bi-tag"></i> {{ $segregation->category->name ?? 'Unknown' }}
                                                <i class="bi bi-scale-fill ms-3"></i> {{ $segregation->quantity }} kg
                                            </p>
                                            @if($segregation->description)
                                                <small class="text-muted">{{ $segregation->description }}</small>
                                            @endif
                                        </div>
                                        <div class="col-md-4 text-end">
                                            @php
                                                $accuracyClass = $segregation->accuracy >= 90 ? 'excellent' : 
                                                               ($segregation->accuracy >= 80 ? 'good' : 
                                                               ($segregation->accuracy >= 60 ? 'fair' : 'poor'));
                                            @endphp
                                            <span class="accuracy-badge accuracy-{{ $accuracyClass }}">
                                                {{ $segregation->accuracy }}%
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $segregation->created_at->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-recycle text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">No segregation history yet</p>
                                <p class="text-muted">Start by submitting your first waste segregation!</p>
                            </div>
                        @endif
                        
                        <div class="text-center mt-4">
                            <a href="{{ route('segregation.history') }}" class="btn btn-outline-primary">
                                View Full History
                            </a>
                        </div>
                    </div>
                    
                    <!-- Tips -->
                    <div class="tab-pane fade" id="tips" role="tabpanel">
                        <div class="educational-section">
                            <h4 class="mb-3">Segregation Tips</h4>
                            <div class="row">
                                @foreach($educationalContent['categories'] as $category)
                                    <div class="col-md-6 mb-3">
                                        <div class="category-card">
                                            <h5 class="text-success">{{ $category['name'] }} Tips</h5>
                                            <ul class="text-dark">
                                                @foreach($category['tips'] as $tip)
                                                    <li>{{ $tip }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="educational-section">
                            <h4 class="mb-3">General Guidelines</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="tip-card">
                                        <strong class="text-dark">Do's:</strong>
                                        <ul class="mb-0 text-dark">
                                            <li>Clean containers before recycling</li>
                                            <li>Separate different materials</li>
                                            <li>Check local guidelines</li>
                                            <li>Educate family members</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="tip-card">
                                        <strong class="text-dark">Don'ts:</strong>
                                        <ul class="mb-0 text-dark">
                                            <li>Mix different waste types</li>
                                            <li>Recycle dirty containers</li>
                                            <li>Ignore hazardous waste</li>
                                            <li>Use wrong bins</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
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

<script>
document.getElementById('segregationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("segregation.submit") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        const resultDiv = document.getElementById('result');
        const resultAlert = document.getElementById('resultAlert');
        const resultMessage = document.getElementById('resultMessage');
        const accuracyInfo = document.getElementById('accuracyInfo');
        
        resultMessage.textContent = data.message;
        accuracyInfo.innerHTML = `
            <strong>Accuracy:</strong> ${data.accuracy}%<br>
            <strong>Status:</strong> ${data.status}<br>
            <strong>Feedback:</strong> ${data.feedback}
        `;
        
        if (data.status === 'correct') {
            resultAlert.className = 'alert alert-success';
        } else {
            resultAlert.className = 'alert alert-warning';
        }
        
        resultDiv.style.display = 'block';
        
        // Reset form
        this.reset();
        
        // Refresh page after 3 seconds to update statistics
        setTimeout(() => {
            location.reload();
        }, 3000);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});
</script>

</body>
</html>
