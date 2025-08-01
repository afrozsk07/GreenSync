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
            // Add missing columns that the model expects
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('destination_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->timestamp('estimated_departure')->nullable();
            $table->timestamp('estimated_arrival')->nullable();
            $table->timestamp('actual_departure')->nullable();
            $table->timestamp('actual_arrival')->nullable();
            $table->string('current_location')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('last_updated')->nullable();
            
            // Rename waste_collection_id to collection_id to match model
            $table->renameColumn('waste_collection_id', 'collection_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transportations', function (Blueprint $table) {
            // Drop added columns
            $table->dropForeign(['user_id', 'driver_id', 'destination_id']);
            $table->dropColumn([
                'user_id', 'driver_id', 'destination_id', 'estimated_departure',
                'estimated_arrival', 'actual_departure', 'actual_arrival',
                'current_location', 'latitude', 'longitude', 'last_updated'
            ]);
            
            // Rename back
            $table->renameColumn('collection_id', 'waste_collection_id');
        });
    }
};
