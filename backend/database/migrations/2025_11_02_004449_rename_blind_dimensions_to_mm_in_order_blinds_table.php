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
        Schema::table('order_blinds', function (Blueprint $table) {
            $table->renameColumn('width_cm', 'width_m');
            $table->renameColumn('height_cm', 'height_m');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_blinds', function (Blueprint $table) {
            $table->renameColumn('width_m', 'width_cm');
            $table->renameColumn('height_m', 'height_cm');
        });
    }
};
