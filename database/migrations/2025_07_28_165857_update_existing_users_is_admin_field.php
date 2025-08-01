<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing users to have is_admin = 0 if it's null
        DB::table('users')
            ->whereNull('is_admin')
            ->orWhere('is_admin', '')
            ->update(['is_admin' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this as it's just a data fix
    }
};
