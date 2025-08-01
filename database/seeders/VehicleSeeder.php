<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::create([
            'vehicle_number' => 'VH001',
            'model' => 'Ford Transit',
            'type' => 'Waste Collection Truck',
            'status' => 'available',
            'capacity' => 5000.00,
            'fuel_type' => 'Diesel'
        ]);

        Vehicle::create([
            'vehicle_number' => 'VH002',
            'model' => 'Mercedes Sprinter',
            'type' => 'Waste Collection Van',
            'status' => 'available',
            'capacity' => 3000.00,
            'fuel_type' => 'Diesel'
        ]);

        Vehicle::create([
            'vehicle_number' => 'VH003',
            'model' => 'Isuzu NPR',
            'type' => 'Heavy Duty Truck',
            'status' => 'available',
            'capacity' => 8000.00,
            'fuel_type' => 'Diesel'
        ]);
    }
}
