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
            $table->string('customer_first_name')->nullable()->after('customer_phone');
            $table->string('customer_last_name')->nullable()->after('customer_first_name');
            $table->string('customer_address')->nullable()->after('customer_last_name');

            $table->decimal('blind_width_mm', 8, 2)->nullable()->after('status');
            $table->decimal('blind_height_mm', 8, 2)->nullable()->after('blind_width_mm');
            $table->string('blind_image_path')->nullable()->after('blind_height_mm');

            $table->unsignedTinyInteger('calc_multiplier')->default(10)->after('blind_image_path'); // 10 or 11
            $table->decimal('extra_charge', 10, 2)->default(0)->after('calc_multiplier');
            $table->decimal('total_amount', 14, 2)->default(0)->after('extra_charge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_first_name',
                'customer_last_name',
                'customer_address',
                'blind_width_mm',
                'blind_height_mm',
                'blind_image_path',
                'calc_multiplier',
                'extra_charge',
                'total_amount',
            ]);
        });
    }
};
