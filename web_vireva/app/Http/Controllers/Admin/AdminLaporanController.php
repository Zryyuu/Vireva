<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Villa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // 1. Ringkasan Utama (All Time)
        $totalOmzet = Pemesanan::whereIn('status_pembayaran', ['settlement', 'paid'])->sum('total_biaya');
        $totalBooking = Pemesanan::count();
        $totalVilla = Villa::count();

        // 2. Pendapatan Bulanan (Untuk Grafik)
        $monthlyRevenue = Pemesanan::whereIn('status_pembayaran', ['settlement', 'paid'])
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_biaya) as total')
            )
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->all();

        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyRevenue[$i] ?? 0;
        }

        // 3. Villa Terpopuler (Top 5)
        $popularVillas = Villa::withCount(['pemesanan' => function($query) {
                $query->whereIn('status_pembayaran', ['settlement', 'paid']);
            }])
            ->orderBy('pemesanan_count', 'desc')
            ->take(5)
            ->get();

        // 4. Transaksi Terbaru
        $recentTransactions = Pemesanan::with(['tamu', 'villa'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.laporan.index', compact(
            'totalOmzet', 
            'totalBooking', 
            'totalVilla', 
            'chartData', 
            'popularVillas',
            'recentTransactions',
            'year'
        ));
    }
}
