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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tamu_id')->constrained('tamu')->onDelete('cascade');
            $table->foreignId('kamar_id')->constrained('kamar')->onDelete('cascade');
            $table->foreignId('petugas_id')->nullable()->constrained('petugas')->onDelete('set null');
            $table->date('tanggal_checkin');
            $table->date('tanggal_checkout');
            $table->integer('total_hari');
            $table->decimal('total_biaya', 12, 2);
            $table->enum('status_pemesanan', ['menunggu', 'aktif', 'selesai', 'batal'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
