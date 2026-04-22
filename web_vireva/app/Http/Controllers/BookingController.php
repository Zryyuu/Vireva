<?php

namespace App\Http\Controllers;

use App\Models\Villa;
use App\Models\Pemesanan;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function explore()
    {
        $villas = Villa::where('status_villa', 'tersedia')->get();
        return view('bookings.explore', compact('villas'));
    }

    public function index()
    {
        $bookings = auth()->user()->tamu ? auth()->user()->tamu->pemesanan()->latest()->get() : collect();
        return view('bookings.index', compact('bookings'));
    }

    public function create(Villa $villa)
    {
        return view('bookings.create', compact('villa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'tanggal_checkin' => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
        ]);

        $villa = Villa::findOrFail($request->villa_id);
        
        if (!$villa->isAvailable()) {
            return back()->with('error', 'Maaf, villa ini sedang tidak tersedia.');
        }

        $checkin = Carbon::parse($request->tanggal_checkin);
        $checkout = Carbon::parse($request->tanggal_checkout);
        $total_hari = $checkin->diffInDays($checkout);
        $total_biaya = $total_hari * $villa->harga_permalam;

        // Ensure user has a Tamu profile
        $tamu = auth()->user()->tamu;
        if (!$tamu) {
            $tamu = Tamu::create([
                'user_id' => auth()->id(),
                'nama_tamu' => auth()->user()->name,
            ]);
        }

        $pemesanan = Pemesanan::create([
            'tamu_id' => $tamu->id,
            'villa_id' => $villa->id,
            'tanggal_checkin' => $request->tanggal_checkin,
            'tanggal_checkout' => $request->tanggal_checkout,
            'total_hari' => $total_hari,
            'total_biaya' => $total_biaya,
            'status_pemesanan' => 'menunggu',
        ]);

        return redirect()->route('bookings.index')->with('success', 'Reservasi berhasil dibuat. Silakan lakukan pembayaran.');
    }

    public function show(Pemesanan $pemesanan)
    {
        return view('bookings.show', compact('pemesanan'));
    }

    public function cancel(Pemesanan $pemesanan)
    {
        if ($pemesanan->tamu->user_id !== auth()->id()) {
            abort(403);
        }

        if ($pemesanan->status_pemesanan !== 'menunggu') {
            return back()->with('error', 'Reservasi tidak dapat dibatalkan.');
        }

        $pemesanan->update(['status_pemesanan' => 'batal']);

        return back()->with('success', 'Reservasi telah dibatalkan.');
    }
}
