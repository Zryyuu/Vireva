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
        $month = $request->get('month');
        
        // 1. Ringkasan Utama (Filtered by Year & Month)
        $queryOmzet = Pemesanan::whereIn('status_pembayaran', ['settlement', 'paid'])
            ->whereYear('created_at', $year);
            
        $queryBooking = Pemesanan::whereIn('status_pembayaran', ['settlement', 'paid'])
            ->whereYear('created_at', $year);
        
        $trxQuery = Pemesanan::with(['tamu', 'villa'])
            ->whereYear('created_at', $year);

        if ($month) {
            $queryOmzet->whereMonth('created_at', $month);
            $queryBooking->whereMonth('created_at', $month);
            $trxQuery->whereMonth('created_at', $month);
        }

        $totalOmzet = $queryOmzet->sum('total_biaya');
        $totalBooking = $queryBooking->count();
        
        // 2. Biaya Operasional (Filtered)
        $queryBiaya = \App\Models\Biaya::whereYear('tanggal', $year);
        if ($month) {
            $queryBiaya->whereMonth('tanggal', $month);
        }
        $totalBiaya = $queryBiaya->sum('jumlah');
        $totalLaba = $totalOmzet - $totalBiaya;

        // 3. Pendapatan Bulanan (Untuk Grafik - Selalu 12 bulan)
        $monthlyRevenue = Pemesanan::whereIn('status_pembayaran', ['settlement', 'paid'])
            ->whereYear('created_at', $year)
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('n'); // ambil angka bulan (1-12)
            })
            ->map(function($month) {
                return $month->sum('total_biaya');
            });

        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyRevenue->get($i, 0);
        }

        // 3b. Pengeluaran Bulanan (Untuk Grafik - negatif supaya ke bawah)
        $monthlyExpense = \App\Models\Biaya::whereYear('tanggal', $year)
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->tanggal)->format('n');
            })
            ->map(function($month) {
                return $month->sum('jumlah');
            });

        $expenseChartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $expenseChartData[] = $monthlyExpense->get($i, 0); // positif, 0 di bawah
        }

        // 4. Villa Terpopuler (Top 5)
        $popularVillas = Villa::withCount(['pemesanan' => function($query) use ($year, $month) {
                $query->whereIn('status_pembayaran', ['settlement', 'paid'])
                      ->whereYear('created_at', $year);
                if ($month) {
                    $query->whereMonth('created_at', $month);
                }
            }])
            ->orderBy('pemesanan_count', 'desc')
            ->take(5)
            ->get();

        // 5. Transaksi Terbaru (Filtered by Month)
        $recentTransactions = $trxQuery->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('admin.laporan.index', compact(
            'totalOmzet', 
            'totalBooking', 
            'totalBiaya', 
            'totalLaba',
            'chartData',
            'expenseChartData',
            'popularVillas',
            'recentTransactions',
            'year',
            'month'
        ));

    }
}
