<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Villa;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'tanggal_checkin' => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
        ]);

        $user = $request->user();
        
        // Find or create Tamu record for this user
        $tamu = Tamu::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_tamu' => $user->name,
                'no_identitas' => 'API-AUTO-' . $user->id,
                'no_hape' => '00000000',
                'alamat' => 'Mobile App User'
            ]
        );

        $villa = Villa::findOrFail($request->villa_id);
        
        $checkin = Carbon::parse($request->tanggal_checkin);
        $checkout = Carbon::parse($request->tanggal_checkout);
        $totalDays = $checkin->diffInDays($checkout);
        $totalBiaya = $totalDays * $villa->harga_permalam;

        $booking = Pemesanan::create([
            'tamu_id' => $tamu->id,
            'villa_id' => $villa->id,
            'tanggal_checkin' => $request->tanggal_checkin,
            'tanggal_checkout' => $request->tanggal_checkout,
            'total_hari' => $totalDays,
            'total_biaya' => $totalBiaya,
            'status_pemesanan' => 'menunggu',
        ]);

        return response()->json([
            'message' => 'Pemesanan berhasil dibuat!',
            'booking' => $booking
        ], 201);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $tamu = Tamu::where('user_id', $user->id)->first();

        if (!$tamu) {
            return response()->json([]);
        }

        $bookings = Pemesanan::with('villa')
            ->where('tamu_id', $tamu->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($bookings);
    }
}
