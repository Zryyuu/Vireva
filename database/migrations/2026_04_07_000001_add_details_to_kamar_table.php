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
        Schema::table('kamar', function (Blueprint $table) {
            $table->integer('kapasitas')->default(2)->after('harga_permalam');
            $table->text('deskripsi')->nullable()->after('kapasitas');
            $table->string('foto')->nullable()->after('deskripsi');
            $table->json('fasilitas')->nullable()->after('foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            $table->dropColumn(['kapasitas', 'deskripsi', 'foto', 'fasilitas']);
        });
    }
};
