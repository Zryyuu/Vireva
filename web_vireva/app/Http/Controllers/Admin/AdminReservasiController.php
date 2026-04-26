<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class AdminReservasiController extends Controller
{
    /**
     * Display a listing of reservations focused on scheduling and check-in/out.
     */
    public function index(Request $request)
    {
        $query = Pemesanan::with(['tamu', 'villa'])
            ->orderBy('tanggal_checkin', 'asc');

        // Optional filter based on status
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status_pemesanan', $request->status);
        }

        $reservasi = $query->paginate(15);
        $statusFilter = $request->status ?? 'semua';

        return view('admin.reservasi.index', compact('reservasi', 'statusFilter'));
    }
}
