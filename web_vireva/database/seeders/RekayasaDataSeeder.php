<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Villa;
use App\Models\User;
use App\Models\Tamu;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RekayasaDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat 17 Villa
        $fasilitas_options = ['WiFi', 'Kolam Renang Privat', 'Dapur Terbuka', 'Smart TV', 'Water Heater', 'Area Parkir Luas', 'BBQ Grill', 'Taman Tropis', 'Balkon View', 'Bathtub'];
        
        $villas_created = [];
        $tipe_villas = ['Standard', 'Deluxe', 'Premium', 'Luxury', 'Family Suite'];
        $image_options = [
            'villas/villa_ext.png',
            'villas/villa_living.png',
            'villas/villa_ocean.png',
            'villas/villa_suite.png',
        ];
        
        for ($i = 1; $i <= 17; $i++) {
            // Pilih 4-7 fasilitas acak
            $f_count = rand(4, 7);
            $f_random = array_rand(array_flip($fasilitas_options), $f_count);
            
            $harga = rand(15, 65) * 100000; // 1.5jt - 6.5jt
            
            $villa = Villa::create([
                'nama_villa' => 'Villa ' . strtoupper(Str::random(5)) . ' ' . rand(10, 99),
                'tipe_villa' => $tipe_villas[array_rand($tipe_villas)],
                'harga_permalam' => $harga,
                'kapasitas' => rand(2, 10),
                'jumlah_bedroom' => rand(1, 4),
                'jumlah_bathroom' => rand(1, 4),
                'luas_bangunan' => rand(80, 300),
                'deskripsi' => 'Villa rekayasa eksklusif dengan pemandangan menawan, sangat cocok untuk liburan staycation bersama keluarga maupun sahabat. Fasilitas sangat lengkap dan bernuansa premium.',
                'fasilitas' => json_encode(array_values($f_random)), // pastikan re-index array
                'status_villa' => 'tersedia',
                'foto' => [$image_options[array_rand($image_options)]],
            ]);
            $villas_created[] = $villa;
        }

        // 2. Gunakan user tamu dari DatabaseSeeder
        $user = User::where('email', 'tamu@gmail.com')->first();
        
        // Jika belum ada (misal run seeder ini sendirian), baru buat
        if (!$user) {
            $user = User::create([
                'name' => 'Tamu',
                'email' => 'tamu@gmail.com',
                'password' => Hash::make('tamu123'),
                'role' => 'user'
            ]);
        }

        $tamu = Tamu::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_tamu' => $user->name,
                'no_hape' => '081234567890',
                'alamat' => 'Jl. Tamu No. 123'
            ]
        );

        // 3. Buat 15 Pemesanan Acak beberapa bulan ke belakang
        $all_villas = Villa::all();
        
        for ($i = 0; $i < 15; $i++) {
            $random_villa = $all_villas->random();
            $bulan_mundur = rand(0, 5); // 0 sampai 5 bulan lalu
            $hari_mundur = rand(1, 28);
            
            $checkin = Carbon::now()->subMonths($bulan_mundur)->subDays($hari_mundur);
            $durasi = rand(1, 5);
            $checkout = (clone $checkin)->addDays($durasi);
            
            $total_biaya = $random_villa->harga_permalam * $durasi;
            
            $status = ['selesai', 'selesai', 'batal', 'aktif'][rand(0, 3)]; // Peluang 'selesai' lebih besar
            
            // Logika realistis: Jika checkout sudah lewat, status tidak mungkin 'aktif' atau 'menunggu'
            if ($checkout->isPast() && $status == 'aktif') {
                $status = 'selesai';
            }
            
            // Jika booking untuk bulan ini (bulan 0) dan checkin masih depan, bisa aktif/menunggu
            if ($checkin->isFuture()) {
                $status = 'menunggu';
            }

            Pemesanan::create([
                'tamu_id' => $tamu->id,
                'villa_id' => $random_villa->id,
                'tanggal_checkin' => $checkin->format('Y-m-d'),
                'tanggal_checkout' => $checkout->format('Y-m-d'),
                'total_hari' => $durasi,
                'total_biaya' => $total_biaya,
                'status_pemesanan' => $status,
                'status_pembayaran' => $status == 'batal' ? 'cancel' : 'settlement',
                'metode_pembayaran' => 'transfer',
                'created_at' => (clone $checkin)->subDays(rand(1, 14)), // Dipesan beberapa hari sebelum checkin
                'updated_at' => (clone $checkin)->subDays(rand(1, 14)),
            ]);
        }
    }
}
