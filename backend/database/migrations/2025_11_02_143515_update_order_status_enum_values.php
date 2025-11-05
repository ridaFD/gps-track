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
        // Step 1: Expand the ENUM to include both old and new values
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('draft', 'submitted', 'completed', 'cancelled', 'pending', 'verified', 'delivered') DEFAULT 'draft'");
        
        // Step 2: Map old status values to new ones
        DB::table('orders')
            ->where('status', 'submitted')
            ->update(['status' => 'pending']);
        
        DB::table('orders')
            ->where('status', 'cancelled')
            ->update(['status' => 'draft']);
        
        // Step 3: Now modify the ENUM to only have the new values
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('draft', 'pending', 'verified', 'completed', 'delivered') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Expand the ENUM to include old and new values
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('draft', 'pending', 'verified', 'completed', 'delivered', 'submitted', 'cancelled') DEFAULT 'draft'");
        
        // Step 2: Map new status values back to old ones
        DB::table('orders')
            ->where('status', 'pending')
            ->update(['status' => 'submitted']);
        
        DB::table('orders')
            ->where('status', 'verified')
            ->update(['status' => 'submitted']);
        
        DB::table('orders')
            ->where('status', 'delivered')
            ->update(['status' => 'completed']);
        
        // Step 3: Revert to old ENUM
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('draft', 'submitted', 'completed', 'cancelled') DEFAULT 'draft'");
    }
};
