<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function getSnapToken($booking, $user)
    {
        $params = [
            'transaction_details' => [
                'order_id' => 'VIREVA-' . $booking->id . '-' . time(),
                'gross_amount' => (int)$booking->total_biaya,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => $booking->villa_id,
                    'price' => (int)$booking->total_biaya,
                    'quantity' => 1,
                    'name' => 'Reservasi Villa (Order #' . $booking->id . ')',
                ]
            ],
        ];

        return Snap::getSnapToken($params);
    }
}
