<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Midtrans\Notification;
use App\Services\MidtransService;

class PaymentCallbackController extends Controller
{
    public function __construct()
    {
        // Initialize Midtrans Config
        new MidtransService();
    }

    public function handle(Request $request)
    {
        try {
            $notification = new Notification();

            $status = $notification->transaction_status;
            $orderId = $notification->order_id;
            $statusCode = $notification->status_code;
            $grossAmount = $notification->gross_amount;
            $signatureKey = $notification->signature_key;

            // Verifikasi Signature Key (Keamanan Utama)
            $serverKey = config('services.midtrans.server_key');
            $hashed = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signatureKey !== $hashed) {
                return response()->json(['message' => 'Invalid Signature'], 403);
            }

            // Format order_id kita: VIREVA-{id}-{time}
            $parts = explode('-', $orderId);
            $bookingId = $parts[1];

            $booking = Pemesanan::findOrFail($bookingId);

            if ($status == 'settlement' || $status == 'capture') {
                $booking->update([
                    'status_pembayaran' => 'settlement',
                    // Tetap 'menunggu' sampai Admin klik Check-in di hari H
                    'status_pemesanan' => 'menunggu' 
                ]);
                
                // Jangan tandai 'terisi' di sini, biarkan Admin yang melakukannya saat tamu datang (Check-in)
                // Ini agar villa tetap terlihat 'tersedia' untuk admin (meskipun sudah lunas)
                
            } elseif ($status == 'pending') {
                $booking->update(['status_pembayaran' => 'pending']);
            } elseif ($status == 'deny' || $status == 'expire' || $status == 'cancel') {
                $booking->update([
                    'status_pembayaran' => $status,
                    'status_pemesanan' => 'batal'
                ]);
                // Pastikan villa kembali TERSEDIA jika pembayaran gagal/batal
                $booking->villa->update(['status_villa' => 'tersedia']);
            }

            return response()->json(['message' => 'Callback handled successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
