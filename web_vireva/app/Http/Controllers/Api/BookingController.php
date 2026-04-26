<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Villa;
use App\Models\Tamu;
use App\Services\MidtransService;
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

        $checkin = Carbon::parse($request->tanggal_checkin);
        $checkout = Carbon::parse($request->tanggal_checkout);

        // Cek overlap booking
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
            return response()->json([
                'message' => 'Maaf, Villa sudah dipesan untuk tanggal tersebut.'
            ], 422);
        }

        $villa = Villa::findOrFail($request->villa_id);
        
        // Cek status fisik villa
        if ($villa->status_villa === 'maintenance') {
            return response()->json([
                'message' => 'Maaf, Villa sedang dalam perbaikan.'
            ], 422);
        }
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

        // Generate Midtrans Snap Token
        try {
            $midtrans = new MidtransService();
            $snapToken = $midtrans->getSnapToken($booking, $user);
            $booking->update(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            // Jika midtrans gagal, kita biarkan tapi log error
            \Log::error('Midtrans Error: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Pemesanan berhasil dibuat!',
            'booking' => $booking,
            'snap_token' => $booking->snap_token
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
