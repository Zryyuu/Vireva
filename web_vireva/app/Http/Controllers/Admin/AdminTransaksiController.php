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
        $query = Pemesanan::with(['tamu', 'villa', 'pembayaran'])->orderBy('created_at', 'desc');

        // Optional filter based on status
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status_pemesanan', $request->status);
        }

        $transaksi = $query->paginate(15);
        $statusFilter = $request->status ?? 'semua';

        return view('admin.transaksi.index', compact('transaksi', 'statusFilter'));
    }

    /**
     * Process a manual action on transaction (like processing refund).
     */
    public function processAction(Request $request, $id)
    {
        $pemesanan = Pemesanan::with('pembayaran')->findOrFail($id);

        if ($request->action == 'refund') {
            if ($pemesanan->status_pemesanan == 'batal' || $pemesanan->status_pemesanan == 'menunggu') {
                if ($pemesanan->pembayaran && $pemesanan->pembayaran->status_bayar == 'berhasil') {
                    // Update to refunded
                    $pemesanan->pembayaran->update([
                        'status_bayar' => 'refund'
                    ]);
                    $pemesanan->update([
                        'status_pemesanan' => 'batal'
                    ]);
                    return redirect()->back()->with('success', 'Dana refund telah disetujui dan dicatat oleh sistem.');
                }
            }
            return redirect()->back()->with('error', 'Transaksi tidak valid untuk proses refund.');
        }

        // Additional actions like approving manual transfer could be here
        if ($request->action == 'approve') {
            if ($pemesanan->pembayaran) {
                $pemesanan->pembayaran->update(['status_bayar' => 'berhasil']);
            }
            $pemesanan->update(['status_pemesanan' => 'aktif']);
            return redirect()->back()->with('success', 'Pembayaran disetujui. Status reservasi menjadi aktif.');
        }

        return redirect()->back()->with('error', 'Aksi tidak dikenal.');
    }
}
