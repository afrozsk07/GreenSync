<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WasteCollectionController;
use App\Http\Controllers\TransportationController;

use App\Http\Controllers\WasteCategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VehicleController;

Route::middleware('api')->group(function () {
    // Waste Collections
    Route::get('/collections', [WasteCollectionController::class, 'index']);
    Route::post('/collections', [WasteCollectionController::class, 'store']);
    Route::get('/collections/{collection}', [WasteCollectionController::class, 'show']);
    Route::patch('/collections/{collection}', [WasteCollectionController::class, 'update']);

    // Transportations
    Route::get('/transportations', [TransportationController::class, 'index']);
    Route::post('/transportations', [TransportationController::class, 'store']);
    Route::get('/transportations/{transportation}', [TransportationController::class, 'show']);
    Route::patch('/transportations/{transportation}', [TransportationController::class, 'update']);



    // Waste Categories
    Route::get('/waste-categories', [WasteCategoryController::class, 'index']);
    Route::post('/waste-categories', [WasteCategoryController::class, 'store']);
    Route::get('/waste-categories/{category}', [WasteCategoryController::class, 'show']);
    Route::patch('/waste-categories/{category}', [WasteCategoryController::class, 'update']);

    // Locations
    Route::get('/locations', [LocationController::class, 'index']);
    Route::post('/locations', [LocationController::class, 'store']);
    Route::get('/locations/{location}', [LocationController::class, 'show']);
    Route::patch('/locations/{location}', [LocationController::class, 'update']);

    // Vehicles
    Route::get('/vehicles', [VehicleController::class, 'index']);
    Route::post('/vehicles', [VehicleController::class, 'store']);
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);
    Route::patch('/vehicles/{vehicle}', [VehicleController::class, 'update']);
});