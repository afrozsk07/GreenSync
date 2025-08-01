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
        Schema::table('collections', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn(['location', 'category', 'quantity', 'notes']);
            
            // Add new columns
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('request_id')->nullable()->constrained('waste_requests')->onDelete('set null');
            $table->string('waste_type');
            $table->decimal('quantity', 8, 2); // in kg
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->text('address');
            $table->enum('status', ['scheduled', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
            $table->text('collection_notes')->nullable();
            $table->timestamp('actual_pickup_time')->nullable();
            $table->timestamp('completion_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            // Revert changes
            $table->dropForeign(['user_id', 'request_id', 'vehicle_id', 'driver_id']);
            $table->dropColumn([
                'user_id', 'request_id', 'waste_type', 'quantity', 'pickup_date', 'pickup_time', 
                'address', 'status', 'vehicle_id', 'driver_id', 'collection_notes',
                'actual_pickup_time', 'completion_time'
            ]);
            
            // Restore original columns
            $table->string('location');
            $table->string('category');
            $table->decimal('quantity', 8, 2);
            $table->text('notes')->nullable();
        });
    }
};
