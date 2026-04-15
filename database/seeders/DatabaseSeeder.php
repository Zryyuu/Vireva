<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Setup Admin
        User::create([
            'name' => 'Administrator Bohot',
            'email' => 'admin@bohot.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Setup Test Guest
        User::create([
            'name' => 'Tamu',
            'email' => 'tamu@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('tamu123'),
            'role' => 'user',
        ]);

        // Setup Kamar
        \App\Models\Kamar::create([
            'nomor_kamar' => '101',
            'tipe_kamar' => 'Standard',
            'harga_permalam' => 550000,
            'status_kamar' => 'tersedia',
            'kapasitas' => 2,
            'deskripsi' => 'Kamar nyaman dengan pemandangan taman, fasilitas WiFi gratis, dan sarapan pagi.',
        ]);

        \App\Models\Kamar::create([
            'nomor_kamar' => '202',
            'tipe_kamar' => 'Deluxe',
            'harga_permalam' => 850000,
            'status_kamar' => 'tersedia',
            'kapasitas' => 2,
            'deskripsi' => 'Kamar mewah dengan kasur King Size, TV 50 inch, dan bathtub di kamar mandi.',
        ]);

        \App\Models\Kamar::create([
            'nomor_kamar' => '505',
            'tipe_kamar' => 'Suite',
            'harga_permalam' => 1500000,
            'status_kamar' => 'tersedia',
            'kapasitas' => 4,
            'deskripsi' => 'Suite eksklusif dengan ruang tamu terpisah, balkon pribadi menghadap ke kota, dan mini bar.',
        ]);
    }
}
