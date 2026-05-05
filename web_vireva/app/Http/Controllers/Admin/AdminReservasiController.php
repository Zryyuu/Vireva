<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Villa;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminReservasiController extends Controller
{
    /**
     * Display a listing of reservations focused on scheduling and check-in/out.
     */
    public function index(Request $request)
    {
        // Auto-cleanup: Hapus pemesanan pending yang sudah lebih dari 24 jam
        Pemesanan::where('status_pembayaran', 'pending')
            ->where('created_at', '<', now()->subDay())
            ->delete();

        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        $query = Pemesanan::with(['tamu', 'villa'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc');

        // Optional filter based on status
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status_pemesanan', $request->status);
        }

        $reservasi = $query->paginate(15);
        $statusFilter = $request->status ?? 'semua';
        $villas = Villa::all();

        return view('admin.reservasi.index', compact('reservasi', 'statusFilter', 'villas', 'year', 'month'));
    }

    public function verifyPayment(Request $request, $id)
    {
        $booking = Pemesanan::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:settlement,cancel',
            'catatan' => 'nullable',
        ]);

        if ($request->status == 'settlement') {
            $booking->update([
                'status_pembayaran' => 'settlement',
                'catatan_admin' => $request->catatan,
            ]);
            return back()->with('success', 'Pembayaran berhasil diverifikasi.');
        } else {
            $booking->update([
                'status_pembayaran' => 'cancel',
                'status_pemesanan' => 'batal',
                'catatan_admin' => $request->catatan,
            ]);
            return back()->with('success', 'Pembayaran ditolak dan reservasi dibatalkan.');
        }
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'nama_tamu' => 'required',
            'no_hape' => 'required',
            'villa_id' => 'required|exists:villas,id',
            'tanggal_checkin' => 'required|date',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'bukti_pembayaran' => 'nullable|image|max:5120',
        ]);

        $checkin = Carbon::parse($request->tanggal_checkin);
        $checkout = Carbon::parse($request->tanggal_checkout);

        // Check availability
        $isBooked = Pemesanan::where('villa_id', $request->villa_id)
            ->whereIn('status_pembayaran', ['settlement', 'pending'])
            ->where(function($query) use ($checkin, $checkout) {
                $query->whereBetween('tanggal_checkin', [$checkin, $checkout->copy()->subMinute()])
                      ->orWhereBetween('tanggal_checkout', [$checkin->copy()->addMinute(), $checkout])
                      ->orWhere(function($q) use ($checkin, $checkout) {
                          $q->where('tanggal_checkin', '<=', $checkin)
                            ->where('tanggal_checkout', '>=', $checkout);
                      });
            })
            ->exists();

        if ($isBooked) {
            return back()->with('error', 'Villa sudah dipesan pada tanggal tersebut.');
        }

        $villa = Villa::findOrFail($request->villa_id);
        $totalDays = $checkin->diffInDays($checkout);
        $totalBiaya = $totalDays * $villa->harga_permalam;

        $tamu = Tamu::firstOrCreate(
            ['no_hape' => $request->no_hape],
            [
                'nama_tamu' => $request->nama_tamu,
                'no_identitas' => 'OFFLINE-' . time(),
                'alamat' => 'Pemesanan Manual/Offline',
            ]
        );

        $path = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('pembayaran', 'public');
        }

        Pemesanan::create([
            'tamu_id' => $tamu->id,
            'villa_id' => $villa->id,
            'petugas_id' => auth()->id(),
            'tanggal_checkin' => $request->tanggal_checkin,
            'tanggal_checkout' => $request->tanggal_checkout,
            'total_hari' => $totalDays,
            'total_biaya' => $totalBiaya,
            'status_pemesanan' => 'menunggu',
            'status_pembayaran' => $path ? 'settlement' : 'pending',
            'metode_pembayaran' => 'manual',
            'bukti_pembayaran' => $path,
        ]);

        return back()->with('success', 'Pemesanan manual berhasil disimpan.');
    }
}
