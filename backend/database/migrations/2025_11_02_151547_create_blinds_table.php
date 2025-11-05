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
        Schema::create('blinds', function (Blueprint $table) {
            $table->id();
            $table->string('color')->unique()->comment('Color name, e.g., Brown, Black, White, etc.');
            $table->string('color_code')->nullable()->comment('Hex color code for display');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('image_attachment_id')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('stock_qty')->default(0)->comment('Current stock quantity');
            $table->integer('low_stock_threshold')->default(10)->comment('Alert when stock falls below this');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['is_active', 'color']);
            $table->index('stock_qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blinds');
    }
};
