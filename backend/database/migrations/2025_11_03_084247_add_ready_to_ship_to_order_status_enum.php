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
        // Add 'ready_to_ship' to the status enum
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('draft', 'pending', 'verified', 'ready_to_ship', 'completed', 'delivered') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'ready_to_ship' from the enum and convert existing values
        DB::table('orders')
            ->where('status', 'ready_to_ship')
            ->update(['status' => 'verified']);
        
        // Remove 'ready_to_ship' from enum
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('draft', 'pending', 'verified', 'completed', 'delivered') DEFAULT 'draft'");
    }
};
