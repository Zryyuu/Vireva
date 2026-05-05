<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class AdminTransaksiController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        $query = Pemesanan::with(['tamu', 'villa', 'pembayaran'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc');

        // Optional filter based on status
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status_pemesanan', $request->status);
        }

        $transaksi = $query->paginate(15);
        $statusFilter = $request->status ?? 'semua';

        return view('admin.transaksi.index', compact('transaksi', 'statusFilter', 'year', 'month'));
    }

    public function processAction(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        // Approve Payment (Manual) — tandai lunas & aktifkan booking
        if ($request->action == 'approve') {
            $pemesanan->update([
                'status_pembayaran' => 'settlement',
                'status_pemesanan'  => 'aktif',
            ]);
            return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi. Reservasi sekarang AKTIF.');
        }

        // Process Check-in
        if ($request->action == 'checkin') {
            if ($pemesanan->status_pembayaran !== 'settlement') {
                return redirect()->back()->with('error', 'Tamu belum melunasi pembayaran.');
            }
            
            $pemesanan->update(['status_pemesanan' => 'aktif']);
            $pemesanan->villa->update(['status_villa' => 'terisi']);
            
            return redirect()->back()->with('success', 'Tamu berhasil Check-in. Unit villa sekarang berstatus TERISI.');
        }

        // Process Check-out
        if ($request->action == 'checkout') {
            $pemesanan->update(['status_pemesanan' => 'selesai']);
            $pemesanan->villa->update(['status_villa' => 'tersedia']);
            
            return redirect()->back()->with('success', 'Tamu berhasil Check-out. Unit villa sekarang TERSEDIA kembali.');
        }

        // Cancel Transaction
        if ($request->action == 'cancel') {
            $pemesanan->update(['status_pemesanan' => 'batal']);
            $pemesanan->villa->update(['status_villa' => 'tersedia']);
            
            return redirect()->back()->with('success', 'Reservasi berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Aksi tidak dikenal.');
    }
}
