<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Collection - GreenSync</title>
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
        .request-form {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .btn-primary {
            background: linear-gradient(135deg, #27AE60, #2ECC71);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2ECC71, #27AE60);
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
        <h1>Request New Collection</h1>
        <p>Submit a request for waste collection pickup</p>
    </div>
</section>

<!-- Main Content -->
<section class="section" style="padding-top: 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="request-form">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Collection Request Form</h4>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <form method="POST" action="{{ route('collections.request') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="waste_type" class="form-label">Waste Type *</label>
                            <select class="form-select" id="waste_type" name="waste_type" required>
                                <option value="">Select waste type</option>
                                <option value="Household Waste">Household Waste</option>
                                <option value="Organic Waste">Organic Waste</option>
                                <option value="Recyclable Waste">Recyclable Waste</option>
                                <option value="Electronic Waste">Electronic Waste</option>
                                <option value="Hazardous Waste">Hazardous Waste</option>
                                <option value="Construction Waste">Construction Waste</option>
                                <option value="Medical Waste">Medical Waste</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity (kg) *</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   min="0.1" max="1000" step="0.1" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pickup_date" class="form-label">Pickup Date *</label>
                                    <input type="date" class="form-control" id="pickup_date" name="pickup_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pickup_time" class="form-label">Pickup Time</label>
                                    <input type="time" class="form-control" id="pickup_time" name="pickup_time">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Pickup Address *</label>
                            @if($userAddresses->count() > 0)
                                <div class="mb-2">
                                    <select class="form-select" id="address_select" onchange="selectAddress()">
                                        <option value="">Select saved address</option>
                                        @foreach($userAddresses as $address)
                                            <option value="{{ $address->full_address }}" data-address="{{ $address->full_address }}">
                                                {{ $address->name }} ({{ ucfirst($address->type) }}){{ $address->is_default ? ' - Default' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <textarea class="form-control" id="address" name="address" rows="3" required 
                                      placeholder="Enter pickup address or select from saved addresses above"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Provide additional details about your waste"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="special_instructions" class="form-label">Special Instructions</label>
                            <textarea class="form-control" id="special_instructions" name="special_instructions" rows="2" 
                                      placeholder="Any special handling requirements or instructions"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send me-2"></i>Submit Request
                            </button>
                        </div>
                    </form>
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
// Set minimum date to tomorrow
document.getElementById('pickup_date').min = new Date().toISOString().split('T')[0];

// Address selection function
function selectAddress() {
    const addressSelect = document.getElementById('address_select');
    const addressTextarea = document.getElementById('address');
    
    if (addressSelect.value) {
        addressTextarea.value = addressSelect.value;
    }
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const quantity = document.getElementById('quantity').value;
    const pickupDate = document.getElementById('pickup_date').value;
    const address = document.getElementById('address').value;
    const wasteType = document.getElementById('waste_type').value;
    
    if (!quantity || !pickupDate || !address || !wasteType) {
        e.preventDefault();
        alert('Please fill in all required fields.');
    }
});
</script>
</body>
</html> 