<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Segregation Management - GreenSync Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        .btn-warning-custom {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
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
        
        .category-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
        }
        
        .progress-ring {
            width: 120px;
            height: 120px;
        }
        
        .progress-ring-circle {
            stroke: #667eea;
            stroke-width: 8;
            fill: transparent;
            stroke-dasharray: 283;
            stroke-dashoffset: 283;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
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
                        <i class="fas fa-filter me-3"></i>Waste Segregation Management
                    </h1>
                    <p class="text-muted">Monitor and manage waste segregation activities across the platform</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-2 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-primary">{{ $stats['total_segregations'] }}</div>
                        <div class="stats-label">Total Segregations</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-success">{{ $stats['segregations_today'] }}</div>
                        <div class="stats-label">Today's Segregations</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-info">{{ $stats['total_waste_segregated'] }} kg</div>
                        <div class="stats-label">Total Waste Segregated</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-warning">{{ $stats['categories_count'] }}</div>
                        <div class="stats-label">Waste Categories</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-danger">{{ $stats['active_users'] }}</div>
                        <div class="stats-label">Active Users</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card stats-card text-center">
                    <div class="card-body">
                        <div class="stats-number text-secondary">
                            @if($stats['total_segregations'] > 0)
                                {{ round(($stats['segregations_today'] / $stats['total_segregations']) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </div>
                        <div class="stats-label">Today's Progress</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="content-card p-4">
                    <h4 class="text-primary fw-bold mb-3">
                        <i class="fas fa-chart-pie me-2"></i>Segregation by Category
                    </h4>
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="content-card p-4">
                    <h4 class="text-success fw-bold mb-3">
                        <i class="fas fa-chart-line me-2"></i>Monthly Segregation Trend
                    </h4>
                    <div class="chart-container">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Segregations -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="text-primary fw-bold">
                            <i class="fas fa-list me-2"></i>Recent Segregations
                        </h3>
                        <div>
                            <button class="btn btn-custom me-2" onclick="generateReport()">
                                <i class="fas fa-download me-2"></i>Export Report
                            </button>
                            <button class="btn btn-success-custom" onclick="refreshData()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh
                            </button>
                        </div>
                    </div>
                    @if($segregations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>User</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                    <th>Date</th>
                                        <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                                    @foreach($segregations->take(20) as $segregation)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle me-2 text-primary"></i>
                                                {{ $segregation->user->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="category-badge" style="background-color: {{ $segregation->category->color }}20; color: {{ $segregation->category->color }};">
                                                <i class="{{ $segregation->category->icon }} me-1"></i>
                                                {{ $segregation->category->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ $segregation->quantity }} kg</strong>
                                        </td>
                                        <td>{{ $segregation->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($segregation->notes)
                                                <span class="text-muted">{{ Str::limit($segregation->notes, 50) }}</span>
                                            @else
                                                <span class="text-muted">No notes</span>
                                            @endif
                                        </td>
                                        <td>
                                                                                         <a href="{{ route('admin.admin.segregation.details', $segregation->id) }}" 
                                                class="btn btn-outline-primary btn-sm">
                                                 <i class="fas fa-eye me-1"></i>Details
                                             </a>
                    </td>
                </tr>
                                    @endforeach
            </tbody>
        </table>
                        </div>
                        @if($segregations->count() > 20)
                            <div class="text-center mt-3">
                                <p class="text-muted">Showing 20 of {{ $segregations->count() }} records</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-filter fa-3x mb-3"></i>
                            <p>No segregation records found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Category Management -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="content-card p-4">
                    <h3 class="text-warning fw-bold mb-3">
                        <i class="fas fa-tags me-2"></i>Waste Categories
                    </h3>
                    <div class="row">
                        @foreach($categories as $category)
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="{{ $category->icon }}" style="font-size: 2rem; color: {{ $category->color }};"></i>
                                    </div>
                                    <h5 class="card-title">{{ $category->name }}</h5>
                                    <p class="card-text text-muted">{{ $category->description }}</p>
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-outline-primary btn-sm me-2" onclick="editCategory({{ $category->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteCategory({{ $category->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-custom" onclick="addCategory()">
                            <i class="fas fa-plus me-2"></i>Add New Category
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Progress -->
        <div class="row">
            <div class="col-12">
                <div class="content-card p-4">
                    <h3 class="text-info fw-bold mb-3">
                        <i class="fas fa-users me-2"></i>User Segregation Progress
                    </h3>
                    <div class="row">
                        @foreach($users->take(6) as $user)
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-user-circle" style="font-size: 3rem; color: #667eea;"></i>
                                    </div>
                                    <h6 class="card-title">{{ $user->name }}</h6>
                                    <p class="card-text text-muted">{{ $user->email }}</p>
                                    <button class="btn btn-outline-info btn-sm" onclick="viewUserProgress({{ $user->id }})">
                                        <i class="fas fa-chart-bar me-1"></i>View Progress
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($segregationsByCategory->pluck('category.name')) !!},
                datasets: [{
                    data: {!! json_encode($segregationsByCategory->pluck('total_quantity')) !!},
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Trend Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Segregations',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function generateReport() {
            const startDate = prompt('Enter start date (YYYY-MM-DD):', '{{ now()->subDays(30)->format("Y-m-d") }}');
            const endDate = prompt('Enter end date (YYYY-MM-DD):', '{{ now()->format("Y-m-d") }}');
            
            if (startDate && endDate) {
                window.location.href = `/admin/admin/segregation/export?start_date=${startDate}&end_date=${endDate}`;
            }
        }

        function refreshData() {
            location.reload();
        }

        function viewUserProgress(userId) {
            fetch(`/admin/admin/segregation/user-progress/${userId}`)
                .then(response => response.json())
                .then(data => {
                    alert(`User Progress:\nTotal Segregations: ${data.total_segregations}\nTotal Quantity: ${data.total_quantity} kg\nCategories Used: ${data.categories_used}`);
                })
                .catch(error => {
                    alert('Error loading user progress');
                });
        }

        function addCategory() {
            const name = prompt('Enter category name:');
            const description = prompt('Enter category description:');
            const color = prompt('Enter color (hex):', '#667eea');
            const icon = prompt('Enter icon class:', 'fas fa-recycle');
            
            if (name && color && icon) {
                // Submit form data
                const formData = new FormData();
                formData.append('name', name);
                formData.append('description', description || '');
                formData.append('color', color);
                formData.append('icon', icon);
                formData.append('_token', '{{ csrf_token() }}');
                
                fetch('/admin/admin/segregation/categories', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error creating category');
                    }
                })
                .catch(error => {
                    alert('Error creating category');
                });
            }
        }

        function editCategory(categoryId) {
            // Implementation for editing category
            alert('Edit category functionality will be implemented');
        }

        function deleteCategory(categoryId) {
            if (confirm('Are you sure you want to delete this category?')) {
                fetch(`/admin/admin/segregation/categories/${categoryId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Error deleting category');
                    }
                })
                .catch(error => {
                    alert('Error deleting category');
                });
            }
        }
    </script>
</body>
</html>
