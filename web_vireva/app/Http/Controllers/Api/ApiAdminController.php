<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'foto' => 'nullable|image|max:2048',
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
            'foto' => 'nullable|image|max:2048',
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

    public function processBookingAction(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $action = $request->action;

        if ($action == 'checkin') {
            if ($pemesanan->status_pembayaran !== 'settlement') {
                return response()->json(['message' => 'Tamu belum melunasi pembayaran.'], 400);
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
