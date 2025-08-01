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
        Schema::create('segregations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('waste_type');
            $table->foreignId('category_id')->constrained('waste_categories')->onDelete('cascade');
            $table->decimal('quantity', 8, 2);
            $table->text('description')->nullable();
            $table->decimal('accuracy', 5, 2)->default(0);
            $table->enum('status', ['correct', 'needs_review'])->default('needs_review');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segregations');
    }
};
