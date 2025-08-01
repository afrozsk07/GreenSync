<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transportations', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn(['vehicle_number', 'driver_name']);
            
            // Add new columns
            $table->foreignId('waste_collection_id')->constrained('collections')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('departure_time')->nullable();
            $table->timestamp('arrival_time')->nullable();
            $table->string('start_location');
            $table->string('destination');
            $table->enum('status', ['scheduled', 'in_transit', 'completed', 'cancelled'])->default('scheduled');
            $table->text('route_details')->nullable();
            $table->text('driver_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transportations', function (Blueprint $table) {
            // Revert changes
            $table->dropForeign(['waste_collection_id', 'vehicle_id']);
            $table->dropColumn([
                'waste_collection_id', 'vehicle_id', 'departure_time', 'arrival_time',
                'start_location', 'destination', 'status', 'route_details', 'driver_notes'
            ]);
            
            // Restore original columns
            $table->string('vehicle_number');
            $table->string('driver_name');
        });
    }
};
