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
            $table->string('status')->default('pending')->change();
        });
        Schema::table('installations', function (Blueprint $table) {
            $table->string('status')->default('scheduled')->change();
        });
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->string('status')->nullable(false)->default('scheduled')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default(null)->change();
        });
        Schema::table('installations', function (Blueprint $table) {
            $table->string('status')->default(null)->change();
        });
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->string('status')->nullable()->default(null)->change();
        });
    }
};
