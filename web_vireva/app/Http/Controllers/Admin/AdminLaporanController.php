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
            
        $queryBooking = Pemesanan::whereYear('created_at', $year);

        if ($month) {
            $queryOmzet->whereMonth('created_at', $month);
            $queryBooking->whereMonth('created_at', $month);
        }

        $totalOmzet = $queryOmzet->sum('total_biaya');
        $totalBooking = $queryBooking->count();
        
        // 2. Biaya Operasional (Filtered)
        $queryBiaya = \App\Models\Biaya::whereYear('tanggal', $year);
        if ($month) {
            $queryBiaya->whereMonth('tanggal', $month);
        }
        $totalBiaya = $queryBiaya->sum('jumlah');

        // 3. Pendapatan Bulanan (Untuk Grafik - Selalu 12 bulan)
        $monthlyRevenue = Pemesanan::whereIn('status_pembayaran', ['settlement', 'paid'])
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('MONTH(created_at) as month_num'),
                DB::raw('SUM(total_biaya) as total')
            )
            ->groupBy('month_num')
            ->get()
            ->pluck('total', 'month_num')
            ->all();

        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyRevenue[$i] ?? 0;
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
        $trxQuery = Pemesanan::with(['tamu', 'villa'])
            ->whereYear('created_at', $year);
        
        if ($month) {
            $trxQuery->whereMonth('created_at', $month);
        }

        $recentTransactions = $trxQuery->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('admin.laporan.index', compact(
            'totalOmzet', 
            'totalBooking', 
            'totalBiaya', 
            'chartData', 
            'popularVillas',
            'recentTransactions',
            'year',
            'month'
        ));
    }
}
