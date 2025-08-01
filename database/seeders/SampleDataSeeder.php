<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WasteRequest;
use App\Models\WasteCollection;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
use App\Models\Transportation;
use App\Models\WasteCategory;
use App\Models\Segregation;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get a regular user
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password123'),
                'is_admin' => 0
            ]
        );

        // TODO: Add your real users here to preserve them during seeding
        // Example:
        // User::firstOrCreate(
        //     ['email' => 'your-email@example.com'],
        //     [
        //         'name' => 'Your Name',
        //         'password' => Hash::make('your-password'),
        //         'is_admin' => 0
        //     ]
        // );

        // Create vehicles
        $vehicle1 = Vehicle::firstOrCreate(
            ['vehicle_number' => 'VH001'],
            [
                'model' => 'Ford Transit',
                'type' => 'Truck',
                'capacity' => 1000,
                'status' => 'available',
                'fuel_type' => 'Diesel'
            ]
        );

        $vehicle2 = Vehicle::firstOrCreate(
            ['vehicle_number' => 'VH002'],
            [
                'model' => 'Mercedes Sprinter',
                'type' => 'Van',
                'capacity' => 500,
                'status' => 'available',
                'fuel_type' => 'Diesel'
            ]
        );

        // Create drivers
        $driver1 = Driver::firstOrCreate(
            ['license_number' => 'DL123456'],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike.johnson@example.com',
                'phone' => '+1234567890',
                'status' => 'available'
            ]
        );

        $driver2 = Driver::firstOrCreate(
            ['license_number' => 'DL789012'],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah.wilson@example.com',
                'phone' => '+1234567891',
                'status' => 'available'
            ]
        );

        // Create sample waste requests
        $request1 = WasteRequest::firstOrCreate(
            [
                'user_id' => $user->id,
                'waste_type' => 'Household Waste',
                'pickup_date' => '2025-01-15'
            ],
            [
                'quantity' => 25.5,
                'pickup_time' => '09:00:00',
                'address' => '123 Main Street, City, State 12345',
                'description' => 'Regular household waste including kitchen waste and general trash',
                'priority' => 'medium',
                'status' => 'pending'
            ]
        );

        $request2 = WasteRequest::firstOrCreate(
            [
                'user_id' => $user->id,
                'waste_type' => 'Recyclable Waste',
                'pickup_date' => '2025-01-20'
            ],
            [
                'quantity' => 15.0,
                'pickup_time' => '14:00:00',
                'address' => '123 Main Street, City, State 12345',
                'description' => 'Paper, plastic, and metal recyclables',
                'priority' => 'low',
                'status' => 'approved'
            ]
        );

        $request3 = WasteRequest::firstOrCreate(
            [
                'user_id' => $user->id,
                'waste_type' => 'Organic Waste',
                'pickup_date' => '2025-01-25'
            ],
            [
                'quantity' => 30.0,
                'pickup_time' => '10:00:00',
                'address' => '123 Main Street, City, State 12345',
                'description' => 'Garden waste and food scraps',
                'priority' => 'high',
                'status' => 'approved'
            ]
        );

        // Create sample collections
        $collection1 = WasteCollection::firstOrCreate(
            [
                'user_id' => $user->id,
                'request_id' => $request2->id
            ],
            [
                'waste_type' => 'Recyclable Waste',
                'quantity' => 15.0,
                'pickup_date' => '2025-01-20',
                'pickup_time' => '14:00:00',
                'address' => '123 Main Street, City, State 12345',
                'status' => 'completed',
                'vehicle_id' => $vehicle1->id,
                'driver_id' => $driver1->id,
                'collection_notes' => 'Successfully collected all recyclables',
                'actual_pickup_time' => '2025-01-20 14:15:00',
                'completion_time' => '2025-01-20 15:30:00'
            ]
        );

        $collection2 = WasteCollection::firstOrCreate(
            [
                'user_id' => $user->id,
                'request_id' => $request3->id
            ],
            [
                'waste_type' => 'Organic Waste',
                'quantity' => 30.0,
                'pickup_date' => '2025-01-25',
                'pickup_time' => '10:00:00',
                'address' => '123 Main Street, City, State 12345',
                'status' => 'in_progress',
                'vehicle_id' => $vehicle2->id,
                'driver_id' => $driver2->id,
                'collection_notes' => 'Collection in progress',
                'actual_pickup_time' => '2025-01-25 10:05:00'
            ]
        );

        // Create transportation records
        Transportation::firstOrCreate(
            [
                'collection_id' => $collection1->id
            ],
            [
                'user_id' => $user->id,
                'vehicle_id' => $vehicle1->id,
                'driver_id' => $driver1->id,
                'destination_id' => 1,
                'estimated_departure' => '2025-01-20 15:30:00',
                'estimated_arrival' => '2025-01-20 16:15:00',
                'actual_departure' => '2025-01-20 15:30:00',
                'actual_arrival' => '2025-01-20 16:15:00',
                'current_location' => 'GreenSync Processing Center, Industrial Zone',
                'status' => 'completed'
            ]
        );

        Transportation::firstOrCreate(
            [
                'collection_id' => $collection2->id
            ],
            [
                'user_id' => $user->id,
                'vehicle_id' => $vehicle2->id,
                'driver_id' => $driver2->id,
                'destination_id' => 1,
                'estimated_departure' => '2025-01-25 10:30:00',
                'estimated_arrival' => '2025-01-25 11:15:00',
                'actual_departure' => '2025-01-25 10:30:00',
                'current_location' => 'In transit to processing center',
                'status' => 'in_transit'
            ]
        );

        // Create waste categories
        $recyclableCategory = WasteCategory::firstOrCreate(
            ['name' => 'Recyclable Waste'],
            [
                'description' => 'Materials that can be processed and reused',
                'handling_instructions' => 'Clean before recycling, separate by material type',
                'is_hazardous' => false,
                'segregation_requirements' => 'Must be clean and sorted by material'
            ]
        );

        $organicCategory = WasteCategory::firstOrCreate(
            ['name' => 'Organic Waste'],
            [
                'description' => 'Biodegradable waste that can be composted',
                'handling_instructions' => 'Compost when possible, avoid meat and dairy',
                'is_hazardous' => false,
                'segregation_requirements' => 'Keep separate from other waste types'
            ]
        );

        $hazardousCategory = WasteCategory::firstOrCreate(
            ['name' => 'Hazardous Waste'],
            [
                'description' => 'Dangerous materials requiring special handling',
                'handling_instructions' => 'Never mix with regular waste, use designated collection points',
                'is_hazardous' => true,
                'segregation_requirements' => 'Must be handled separately with proper safety measures'
            ]
        );

        $generalCategory = WasteCategory::firstOrCreate(
            ['name' => 'General Waste'],
            [
                'description' => 'Non-recyclable, non-hazardous waste',
                'handling_instructions' => 'Minimize this category, consider alternatives',
                'is_hazardous' => false,
                'segregation_requirements' => 'Should be minimized through proper segregation'
            ]
        );

        // Create sample segregations
        Segregation::firstOrCreate(
            [
                'user_id' => $user->id,
                'waste_type' => 'Plastic bottles',
                'category_id' => $recyclableCategory->id
            ],
            [
                'quantity' => 2.5,
                'description' => 'Clean plastic water bottles',
                'accuracy' => 95.0,
                'status' => 'correct'
            ]
        );

        Segregation::firstOrCreate(
            [
                'user_id' => $user->id,
                'waste_type' => 'Food scraps',
                'category_id' => $organicCategory->id
            ],
            [
                'quantity' => 1.0,
                'description' => 'Kitchen food waste',
                'accuracy' => 90.0,
                'status' => 'correct'
            ]
        );

        Segregation::firstOrCreate(
            [
                'user_id' => $user->id,
                'waste_type' => 'Used batteries',
                'category_id' => $hazardousCategory->id
            ],
            [
                'quantity' => 0.5,
                'description' => 'Household batteries',
                'accuracy' => 85.0,
                'status' => 'correct'
            ]
        );

        $this->command->info('Sample data created successfully!');
        $this->command->info('User: user@example.com / password123');
    }
}
