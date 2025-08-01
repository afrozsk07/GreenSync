<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\CollectionController;
use App\Http\Controllers\User\TransportationController;

use App\Http\Controllers\User\SegregationController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminCollectionController;
use App\Http\Controllers\Admin\AdminTransportationController;
use App\Http\Controllers\Admin\AdminSegregationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// ==================== Test Route for Debugging ====================
Route::get('/test-login', function() {
    $user = App\Models\User::where('email', 'admin@waste.com')->first();
    if ($user) {
        return response()->json([
            'user_exists' => true,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'password_hash' => substr($user->password, 0, 20) . '...',
            'password_check' => Hash::check('admin123', $user->password)
        ]);
    } else {
        return response()->json(['user_exists' => false]);
    }
});

// ==================== Home Page ====================
Route::get('/', function () {
    return view('home');
})->middleware('guest')->name('home');

// ==================== User Authentication ====================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ==================== User Routes ====================
// Dashboard - temporarily without any middleware for testing
Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Collections
    Route::get('/collections', [CollectionController::class, 'index'])->name('collections');
    Route::post('/collections/request', [CollectionController::class, 'requestCollection'])->name('collections.request');
    Route::get('/collections/request', [CollectionController::class, 'showRequestForm'])->name('collections.request.form');
    Route::get('/collections/cancel/{id}', [CollectionController::class, 'cancelRequest'])->name('collections.cancel');
    Route::get('/collections/history', [CollectionController::class, 'viewHistory'])->name('collections.history');
    Route::get('/collections/track/{id}', [CollectionController::class, 'trackStatus'])->name('collections.track');
    
    // Segregation
    Route::get('/segregation', [SegregationController::class, 'index'])->name('segregation');
    Route::get('/segregation/learn', [SegregationController::class, 'learnSegregation'])->name('segregation.learn');
    Route::get('/segregation/guidelines', [SegregationController::class, 'getSegregationGuidelines'])->name('segregation.guidelines');
    Route::get('/segregation/progress', [SegregationController::class, 'trackSegregationProgress'])->name('segregation.progress');
    Route::post('/segregation/submit', [SegregationController::class, 'submitSegregation'])->name('segregation.submit');
    Route::get('/segregation/history', [SegregationController::class, 'viewSegregationHistory'])->name('segregation.history');
    Route::get('/segregation/tips', [SegregationController::class, 'getSegregationTips'])->name('segregation.tips');
    
    // Profile & Addresses
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::put('/profile/addresses/{id}', [ProfileController::class, 'updateAddress'])->name('profile.addresses.update');
    Route::delete('/profile/addresses/{id}', [ProfileController::class, 'deleteAddress'])->name('profile.addresses.delete');
    Route::post('/profile/addresses/{id}/default', [ProfileController::class, 'setDefaultAddress'])->name('profile.addresses.default');
    Route::get('/profile/addresses', [ProfileController::class, 'getAddresses'])->name('profile.addresses.get');
    Route::get('/profile/addresses/default', [ProfileController::class, 'getDefaultAddress'])->name('profile.addresses.default.get');
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// ==================== Admin Authentication ====================
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin login routes
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin-login');
    Route::post('/login/submit', [AdminLoginController::class, 'login'])->name('admin-login.submit');

    // Protected admin routes
    Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [AdminLoginController::class, 'logout'])->name('admin-logout');
        
        // Collections Management
        Route::get('/collections', [AdminCollectionController::class, 'index'])->name('admin.collections');
        Route::get('/collections/approve/{id}', [AdminCollectionController::class, 'approveRequest'])->name('admin.collections.approve');
        Route::get('/collections/reject/{id}', [AdminCollectionController::class, 'rejectRequest'])->name('admin.collections.reject');
        Route::post('/collections/assign/{id}', [AdminCollectionController::class, 'assignVehicle'])->name('admin.collections.assign');
        Route::get('/collections/start/{id}', [AdminCollectionController::class, 'startCollection'])->name('admin.collections.start');
        Route::get('/collections/complete/{id}', [AdminCollectionController::class, 'completeCollection'])->name('admin.collections.complete');
        Route::get('/collections/details/{id}', [AdminCollectionController::class, 'viewCollectionDetails'])->name('admin.collections.details');
        Route::get('/collections/report', [AdminCollectionController::class, 'generateReport'])->name('admin.collections.report');
        Route::get('/vehicles', [AdminCollectionController::class, 'manageVehicles'])->name('admin.vehicles');
        Route::get('/drivers', [AdminCollectionController::class, 'manageDrivers'])->name('admin.drivers');
        
        // Transportation Management
        Route::get('/transportation', [AdminTransportationController::class, 'index'])->name('admin.transportation');
        Route::get('/transportation/create', [AdminTransportationController::class, 'index'])->name('admin.transportation.create');
        Route::post('/transportation/create', [AdminTransportationController::class, 'createTransport'])->name('admin.transportation.create.post');
        Route::get('/transportation/start/{id}', [AdminTransportationController::class, 'startTransport'])->name('admin.transportation.start');
        Route::post('/transportation/location/{id}', [AdminTransportationController::class, 'updateLocation'])->name('admin.transportation.location');
        Route::get('/transportation/complete/{id}', [AdminTransportationController::class, 'completeTransport'])->name('admin.transportation.complete');
        Route::get('/transportation/details/{id}', [AdminTransportationController::class, 'viewTransportDetails'])->name('admin.transportation.details');
        Route::get('/transportation/report', [AdminTransportationController::class, 'generateTransportReport'])->name('admin.transportation.report');
        Route::get('/routes', [AdminTransportationController::class, 'manageRoutes'])->name('admin.routes');
        Route::get('/vehicle-tracking', [AdminTransportationController::class, 'trackAllVehicles'])->name('admin.vehicle-tracking');
        Route::get('/vehicle-location/{id}', [AdminTransportationController::class, 'getVehicleLocation'])->name('admin.vehicle-location');
        
        // Segregation Management
        Route::get('/segregation', [AdminSegregationController::class, 'index'])->name('admin.segregation');
        Route::get('/segregation/details/{id}', [AdminSegregationController::class, 'viewSegregationDetails'])->name('admin.segregation.details');
        Route::get('/segregation/report', [AdminSegregationController::class, 'generateSegregationReport'])->name('admin.segregation.report');
        Route::get('/segregation/export', [AdminSegregationController::class, 'exportSegregationData'])->name('admin.segregation.export');
        Route::get('/segregation/user-progress/{id}', [AdminSegregationController::class, 'userSegregationProgress'])->name('admin.segregation.user-progress');
        
        // Category Management
        Route::get('/segregation/categories', [AdminSegregationController::class, 'manageCategories'])->name('admin.segregation.categories');
        Route::post('/segregation/categories', [AdminSegregationController::class, 'createCategory'])->name('admin.segregation.categories.create');
        Route::put('/segregation/categories/{id}', [AdminSegregationController::class, 'updateCategory'])->name('admin.segregation.categories.update');
        Route::delete('/segregation/categories/{id}', [AdminSegregationController::class, 'deleteCategory'])->name('admin.segregation.categories.delete');
    });
});
