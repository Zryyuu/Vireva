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
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('total_biaya');
            $table->enum('status_pembayaran', ['pending', 'settlement', 'expire', 'cancel'])->default('pending')->after('snap_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'status_pembayaran']);
        });
    }
};
