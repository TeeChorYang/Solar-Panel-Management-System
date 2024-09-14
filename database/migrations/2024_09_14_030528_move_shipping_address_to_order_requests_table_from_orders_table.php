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
            $table->dropColumn('shipping_address');
        });
        Schema::table('order_requests', function (Blueprint $table) {
            $table->text('shipping_address')->nullable()->default(null)->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('shipping_address')->nullable()->default(null)->after('shipping_fees');
        });
        Schema::table('order_requests', function (Blueprint $table) {
            $table->dropColumn('shipping_address');
        });
    }
};
