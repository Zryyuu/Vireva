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
        
        // 1. Sedang Menginap (Stays that are currently active)
        $currentlyStaying = Pemesanan::where('status_pemesanan', 'aktif')->count();

        // 2. Check-in Terjadwal (Upcoming stays for current month)
        $upcomingCheckins = Pemesanan::where('status_pemesanan', 'menunggu')
                            ->where('tanggal_checkin', '>=', today())
                            ->whereMonth('tanggal_checkin', now()->month)
                            ->whereYear('tanggal_checkin', now()->year)
                            ->count();
        
        // 3. Performa Reservasi (Bulan Ini)
        $monthlyTotal = Pemesanan::whereIn('status_pemesanan', ['menunggu', 'aktif', 'selesai'])
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count();
        
        // Financial Metrics - Current Month
        $grossRevenue = Pemesanan::whereIn('status_pembayaran', ['settlement', 'paid'])
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->sum('total_biaya');

        // Chart Data: 7 days ago (as originally intended for mobile chart)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenue = Pemesanan::whereDate('created_at', $date)
                ->whereIn('status_pembayaran', ['settlement', 'paid'])
                ->sum('total_biaya');
            
            $chartData[] = [
                'day' => now()->subDays($i)->format('D'),
                'revenue' => (float)$revenue
            ];
        }

        $responseData = [
            'villas' => [
                'total' => $totalVillas,
                'staying' => $currentlyStaying,
            ],
            'bookings' => [
                'total' => $monthlyTotal,
                'upcoming' => $upcomingCheckins,
            ],
            'revenue' => [
                'total' => (float)$grossRevenue,
                'formatted' => 'Rp ' . number_format($grossRevenue, 0, ',', '.'),
            ],
            'chart_data' => $chartData
        ];

        \Illuminate\Support\Facades\Log::info('Admin stats response: ' . json_encode($responseData));

        return response()->json($responseData);
    }
}
