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
        // 1. Rename kamar table to villas
        Schema::rename('kamar', 'villas');

        // 2. Update columns in villas table
        Schema::table('villas', function (Blueprint $table) {
            $table->renameColumn('nomor_kamar', 'nama_villa');
            $table->renameColumn('tipe_kamar', 'tipe_villa');
            $table->renameColumn('status_kamar', 'status_villa');
            
            // Add new villa-specific columns
            $table->integer('jumlah_bedroom')->default(1)->after('harga_permalam');
            $table->integer('jumlah_bathroom')->default(1)->after('jumlah_bedroom');
            $table->integer('luas_bangunan')->nullable()->after('jumlah_bathroom');
        });

        // 3. Update pemesanan table
        Schema::table('pemesanan', function (Blueprint $table) {
            // Rename foreign key column
            $table->renameColumn('kamar_id', 'villa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->renameColumn('villa_id', 'kamar_id');
        });

        Schema::table('villas', function (Blueprint $table) {
            $table->dropColumn(['jumlah_bedroom', 'jumlah_bathroom', 'luas_bangunan']);
            
            $table->renameColumn('status_villa', 'status_kamar');
            $table->renameColumn('tipe_villa', 'tipe_kamar');
            $table->renameColumn('nama_villa', 'nomor_kamar');
        });

        Schema::rename('villas', 'kamar');
    }
};
