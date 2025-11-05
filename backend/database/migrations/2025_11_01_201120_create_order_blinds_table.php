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
        Schema::create('order_blinds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->decimal('width_cm', 8, 2);
            $table->decimal('height_cm', 8, 2);
            $table->unsignedBigInteger('image_attachment_id')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedTinyInteger('calc_multiplier')->default(10); // 10 or 11
            $table->decimal('extra_charge', 10, 2)->default(0);
            $table->decimal('total_price', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_blinds');
    }
};
