<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\Pemesanan;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ApiAdminController extends Controller
{
    public function storeVilla(Request $request)
    {
        $validated = $request->validate([
            'nama_villa' => 'required|unique:villas',
            'tipe_villa' => 'required',
            'harga_permalam' => 'required|numeric',
            'kapasitas' => 'required|integer',
            'jumlah_bedroom' => 'required|integer',
            'jumlah_bathroom' => 'required|integer',
            'luas_bangunan' => 'nullable|integer',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('villas', 'public');
        }

        $villa = Villa::create($validated);

        return response()->json([
            'message' => 'Villa berhasil ditambahkan.',
            'data' => $villa
        ], 201);
    }

    public function updateVilla(Request $request, $id)
    {
        $villa = Villa::findOrFail($id);

        $validated = $request->validate([
            'nama_villa' => 'required|unique:villas,nama_villa,' . $id,
            'tipe_villa' => 'required',
            'harga_permalam' => 'required|numeric',
            'kapasitas' => 'required|integer',
            'jumlah_bedroom' => 'required|integer',
            'jumlah_bathroom' => 'required|integer',
            'luas_bangunan' => 'nullable|integer',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('foto')) {
            if ($villa->foto) {
                Storage::disk('public')->delete($villa->foto);
            }
            $validated['foto'] = $request->file('foto')->store('villas', 'public');
        }

        $villa->update($validated);

        return response()->json([
            'message' => 'Villa berhasil diperbarui.',
            'data' => $villa
        ]);
    }

    public function destroyVilla($id)
    {
        $villa = Villa::findOrFail($id);
        
        if ($villa->pemesanan()->where('status_pemesanan', 'aktif')->exists()) {
            return response()->json(['message' => 'Villa tidak dapat dihapus karena memiliki reservasi aktif.'], 400);
        }

        if ($villa->foto) {
            Storage::disk('public')->delete($villa->foto);
        }

        $villa->delete();

        return response()->json(['message' => 'Villa berhasil dihapus.']);
    }

    public function storeBookingManual(Request $request)
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
            return response()->json(['message' => 'Villa sudah dipesan pada tanggal tersebut.'], 422);
        }

        $villa = Villa::findOrFail($request->villa_id);
        $totalDays = $checkin->diffInDays($checkout);
        $totalBiaya = $totalDays * $villa->harga_permalam;

        // Create or find Tamu (Offline Guest) by phone number
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

        $booking = Pemesanan::create([
            'tamu_id' => $tamu->id,
            'villa_id' => $villa->id,
            'petugas_id' => $request->user()->petugas->id ?? null,
            'tanggal_checkin' => $request->tanggal_checkin,
            'tanggal_checkout' => $request->tanggal_checkout,
            'total_hari' => $totalDays,
            'total_biaya' => $totalBiaya,
            'status_pemesanan' => 'menunggu',
            'status_pembayaran' => $path ? 'settlement' : 'pending',
            'metode_pembayaran' => 'manual',
            'bukti_pembayaran' => $path,
        ]);

        return response()->json([
            'message' => 'Pemesanan manual berhasil dibuat.',
            'data' => $booking
        ], 201);
    }

    public function verifyPembayaran(Request $request, $id)
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
            return response()->json(['message' => 'Pembayaran berhasil diverifikasi.']);
        } else {
            $booking->update([
                'status_pembayaran' => 'cancel',
                'status_pemesanan' => 'batal',
                'catatan_admin' => $request->catatan,
            ]);
            return response()->json(['message' => 'Pembayaran ditolak dan reservasi dibatalkan.']);
        }
    }

    public function processBookingAction(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $action = $request->action;

        if ($action == 'checkin') {
            if ($pemesanan->status_pembayaran !== 'settlement') {
                return response()->json(['message' => 'Tamu belum melakukan pembayaran atau pembayaran belum diverifikasi.'], 400);
            }
            
            $pemesanan->update(['status_pemesanan' => 'aktif']);
            $pemesanan->villa->update(['status_villa' => 'terisi']);
            
            return response()->json(['message' => 'Tamu berhasil Check-in.']);
        }

        if ($action == 'checkout') {
            $pemesanan->update(['status_pemesanan' => 'selesai']);
            $pemesanan->villa->update(['status_villa' => 'tersedia']);
            return response()->json(['message' => 'Tamu berhasil Check-out.']);
        }

        if ($action == 'cancel') {
            $pemesanan->update(['status_pemesanan' => 'batal']);
            $pemesanan->villa->update(['status_villa' => 'tersedia']);
            return response()->json(['message' => 'Reservasi dibatalkan.']);
        }

        return response()->json(['message' => 'Aksi tidak valid.'], 400);
    }

    public function listBookings()
    {
        $bookings = Pemesanan::with(['villa', 'tamu'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($bookings);
    }
}
