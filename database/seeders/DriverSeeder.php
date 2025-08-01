<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Driver::create([
            'name' => 'John Smith',
            'email' => 'john.smith@waste.com',
            'phone' => '+1234567890',
            'license_number' => 'DL123456',
            'status' => 'available'
        ]);

        Driver::create([
            'name' => 'Mike Johnson',
            'email' => 'mike.johnson@waste.com',
            'phone' => '+1234567891',
            'license_number' => 'DL123457',
            'status' => 'available'
        ]);

        Driver::create([
            'name' => 'David Wilson',
            'email' => 'david.wilson@waste.com',
            'phone' => '+1234567892',
            'license_number' => 'DL123458',
            'status' => 'available'
        ]);
    }
}
