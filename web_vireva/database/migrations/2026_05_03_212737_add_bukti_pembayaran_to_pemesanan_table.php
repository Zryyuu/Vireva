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
        Schema::table('pemesanan', function (Blueprint $row) {
            $row->string('bukti_pembayaran')->nullable()->after('status_pembayaran');
            $row->text('catatan_admin')->nullable()->after('bukti_pembayaran');
            $row->string('metode_pembayaran')->default('transfer')->after('status_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $row) {
            $row->dropColumn(['bukti_pembayaran', 'catatan_admin', 'metode_pembayaran']);
        });
    }
};
