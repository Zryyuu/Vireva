<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class AdminStatsController extends Controller
{
    public function index()
    {
        $totalVillas = Villa::count();
        $availableVillas = Villa::where('status_villa', 'tersedia')->count();
        $bookedVillas = Villa::where('status_villa', 'terisi')->count();
        $totalBookings = Pemesanan::count();
        $completedBookings = Pemesanan::where('status_pemesanan', 'selesai')->count();
        $pendingBookings = Pemesanan::where('status_pemesanan', 'menunggu')->count();
        
        // Data finansial hanya untuk Superadmin (Logic di Flutter juga akan nge-hide)
        $totalRevenue = Pemesanan::where('status_pembayaran', 'settlement')->sum('total_biaya');

        // Data Grafik 7 hari terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenue = Pemesanan::whereDate('created_at', $date)
                ->where('status_pembayaran', 'settlement')
                ->sum('total_biaya');
            
            $chartData[] = [
                'day' => now()->subDays($i)->format('D'),
                'revenue' => (float)$revenue
            ];
        }

        return response()->json([
            'villas' => [
                'total' => $totalVillas,
                'available' => $availableVillas,
                'booked' => $bookedVillas,
            ],
            'bookings' => [
                'total' => $totalBookings,
                'completed' => $completedBookings,
                'pending' => $pendingBookings,
            ],
            'revenue' => [
                'total' => (float)$totalRevenue,
                'formatted' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
            ],
            'chart_data' => $chartData
        ]);
    }
}
