<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Villa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Setup Super Admin (Idempotent)
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Administrator Vireva',
                'password' => Hash::make('superadmin123'),
                'role' => 'superadmin',
            ]
        );

        // Setup Admin (Idempotent)
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator Vireva',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Setup Test Guest (Idempotent)
        User::updateOrCreate(
            ['email' => 'tamu@gmail.com'],
            [
                'name' => 'Tamu',
                'password' => Hash::make('tamu123'),
                'role' => 'user',
            ]
        );

        // Setup Villa (Idempotent by Name)
        Villa::updateOrCreate(
            ['nama_villa' => 'Villa Amethyst'],
            [
                'tipe_villa' => '1-Bedroom Villa',
                'harga_permalam' => 1550000,
                'status_villa' => 'tersedia',
                'kapasitas' => 2,
                'jumlah_bedroom' => 1,
                'jumlah_bathroom' => 1,
                'luas_bangunan' => 120,
                'deskripsi' => 'Villa pribadi yang tenang dengan kolam renang indoor, desain minimalis modern, dan taman tropis yang asri.',
                'foto' => ['villas/GM2RHvCzpPBWjWeMMqNUPhjG9L9eHfJEJuA5Hpwh.jpg'],
            ]
        );

        Villa::updateOrCreate(
            ['nama_villa' => 'Villa Emerald'],
            [
                'tipe_villa' => '2-Bedroom Villa',
                'harga_permalam' => 2850000,
                'status_villa' => 'tersedia',
                'kapasitas' => 4,
                'jumlah_bedroom' => 2,
                'jumlah_bathroom' => 2,
                'luas_bangunan' => 250,
                'deskripsi' => 'Hunian mewah keluarga dengan pemandangan lembah, dapur lengkap, dan area lounge outdoor yang luas.',
                'foto' => ['villas/LSiYZi4MYaIG0zIwLlbD2KHagijWhIPWwUnDIreD.jpg'],
            ]
        );

        Villa::updateOrCreate(
            ['nama_villa' => 'The Presidential Retreat'],
            [
                'tipe_villa' => 'Presidential Villa',
                'harga_permalam' => 5500000,
                'status_villa' => 'tersedia',
                'kapasitas' => 6,
                'jumlah_bedroom' => 3,
                'jumlah_bathroom' => 4,
                'luas_bangunan' => 450,
                'deskripsi' => 'Puncak kemewahan Vireva. Dilengkapi fasilitas bioskop pribadi, kolam renang infinity, dan layanan butler 24 jam.',
                'foto' => ['villas/rdkhB1k8E4OFzIXwv88HF9XeuW5ajvbRaHGaAEyg.jpg'],
            ]
        );

        $this->call(RekayasaDataSeeder::class);
    }
}
