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
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'blind_width_mm')) {
                $table->renameColumn('blind_width_mm', 'blind_width_cm');
            }
            if (Schema::hasColumn('orders', 'blind_height_mm')) {
                $table->renameColumn('blind_height_mm', 'blind_height_cm');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'blind_width_cm')) {
                $table->renameColumn('blind_width_cm', 'blind_width_mm');
            }
            if (Schema::hasColumn('orders', 'blind_height_cm')) {
                $table->renameColumn('blind_height_cm', 'blind_height_mm');
            }
        });
    }
};
