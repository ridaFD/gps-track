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
            $table->boolean('stock_alert')->default(false)->after('note');
            $table->text('stock_alert_reason')->nullable()->after('stock_alert');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_blinds', function (Blueprint $table) {
            $table->dropColumn(['stock_alert', 'stock_alert_reason']);
        });
    }
};
