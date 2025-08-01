<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - GreenSync</title>
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
        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .address-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-left: 4px solid #27ae60;
        }
        .address-card.default {
            border-left-color: #f39c12;
        }
        .btn-primary {
            background: linear-gradient(135deg, #27AE60, #2ECC71);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2ECC71, #27AE60);
        }
        .btn-outline-success {
            border-color: #27ae60;
            color: #27ae60;
        }
        .btn-outline-success:hover {
            background-color: #27ae60;
            border-color: #27ae60;
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
        .form-control:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }
        .form-select:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }
        .default-badge {
            background: #f39c12;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        .type-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        .type-home { background: #e8f5e8; color: #27ae60; }
        .type-work { background: #fff3cd; color: #856404; }
        .type-other { background: #f8d7da; color: #721c24; }
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
                    <a class="nav-link active" href="{{ route('profile') }}">Profile</a>
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
        <h1>Profile & Addresses</h1>
        <p>Manage your profile and saved addresses</p>
    </div>
</section>

<!-- Main Content -->
<section class="section">
    <div class="container">
        <div class="row">
            <!-- Profile Information -->
            <div class="col-lg-4">
                <div class="profile-card">
                    <h4 class="mb-4">Profile Information</h4>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Member Since</label>
                        <input type="text" class="form-control" value="{{ $user->created_at->format('M d, Y') }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" value="{{ ucfirst($user->getRole()) }}" readonly>
                    </div>
                </div>
            </div>
            
            <!-- Address Management -->
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Saved Addresses</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="bi bi-plus-circle me-2"></i>Add New Address
                        </button>
                    </div>
                    
                    @if($addresses->count() > 0)
                        <div class="row">
                            @foreach($addresses as $address)
                                <div class="col-md-6 mb-3">
                                    <div class="address-card {{ $address->is_default ? 'default' : '' }}">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-1">{{ $address->name }}</h6>
                                                <span class="type-badge type-{{ $address->type }}">
                                                    {{ ucfirst($address->type) }}
                                                </span>
                                                @if($address->is_default)
                                                    <span class="default-badge ms-2">Default</span>
                                                @endif
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" onclick="editAddress({{ $address->id }})">
                                                        <i class="bi bi-pencil me-2"></i>Edit
                                                    </a></li>
                                                    @if(!$address->is_default)
                                                        <li><a class="dropdown-item" href="#" onclick="setDefaultAddress({{ $address->id }})">
                                                            <i class="bi bi-star me-2"></i>Set as Default
                                                        </a></li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteAddress({{ $address->id }})">
                                                        <i class="bi bi-trash me-2"></i>Delete
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="address-details">
                                            <p class="mb-1">{{ $address->address_line1 }}</p>
                                            @if($address->address_line2)
                                                <p class="mb-1">{{ $address->address_line2 }}</p>
                                            @endif
                                            <p class="mb-1">{{ $address->city }}{{ $address->state ? ', ' . $address->state : '' }}{{ $address->postal_code ? ' ' . $address->postal_code : '' }}</p>
                                            @if($address->country)
                                                <p class="mb-0">{{ $address->country }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-geo-alt text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No addresses saved yet</p>
                            <p class="text-muted">Add your first address to get started!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAddressForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Address Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="e.g., Home, Work, Office">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Address Type *</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select type</option>
                                <option value="home">Home</option>
                                <option value="work">Work</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address_line1" class="form-label">Address Line 1 *</label>
                        <input type="text" class="form-control" id="address_line1" name="address_line1" required placeholder="Street address, P.O. box, company name">
                    </div>
                    <div class="mb-3">
                        <label for="address_line2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="address_line2" name="address_line2" placeholder="Apartment, suite, unit, building, floor, etc.">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State/Province</label>
                            <input type="text" class="form-control" id="state" name="state">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="India">
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                        <label class="form-check-label" for="is_default">
                            Set as default address
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAddressForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_address_id" name="address_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_name" class="form-label">Address Name *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_type" class="form-label">Address Type *</label>
                            <select class="form-select" id="edit_type" name="type" required>
                                <option value="home">Home</option>
                                <option value="work">Work</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address_line1" class="form-label">Address Line 1 *</label>
                        <input type="text" class="form-control" id="edit_address_line1" name="address_line1" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address_line2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="edit_address_line2" name="address_line2">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="edit_city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_state" class="form-label">State/Province</label>
                            <input type="text" class="form-control" id="edit_state" name="state">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="edit_postal_code" name="postal_code">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="edit_country" name="country">
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_is_default" name="is_default" value="1">
                        <label class="form-check-label" for="edit_is_default">
                            Set as default address
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-success text-white text-center py-4 mt-5">
    <p class="mb-0">© 2025 GreenSync. All Rights Reserved.</p>
</footer>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Add Address
document.getElementById('addAddressForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("profile.addresses.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});

// Edit Address
function editAddress(addressId) {
    // Get address data and populate form
    fetch(`/profile/addresses/${addressId}`)
        .then(response => response.json())
        .then(address => {
            document.getElementById('edit_address_id').value = address.id;
            document.getElementById('edit_name').value = address.name;
            document.getElementById('edit_type').value = address.type;
            document.getElementById('edit_address_line1').value = address.address_line1;
            document.getElementById('edit_address_line2').value = address.address_line2 || '';
            document.getElementById('edit_city').value = address.city;
            document.getElementById('edit_state').value = address.state || '';
            document.getElementById('edit_postal_code').value = address.postal_code || '';
            document.getElementById('edit_country').value = address.country || '';
            document.getElementById('edit_is_default').checked = address.is_default;
            
            new bootstrap.Modal(document.getElementById('editAddressModal')).show();
        });
}

// Update Address
document.getElementById('editAddressForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const addressId = document.getElementById('edit_address_id').value;
    const formData = new FormData(this);
    
    fetch(`/profile/addresses/${addressId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});

// Delete Address
function deleteAddress(addressId) {
    if (confirm('Are you sure you want to delete this address?')) {
        fetch(`/profile/addresses/${addressId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
}

// Set Default Address
function setDefaultAddress(addressId) {
    fetch(`/profile/addresses/${addressId}/default`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>

</body>
</html> 