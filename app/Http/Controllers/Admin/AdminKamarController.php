<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminKamarController extends Controller
{
    public function index()
    {
        $kamar = Kamar::all();
        return view('admin.kamar.index', compact('kamar'));
    }

    public function create()
    {
        return view('admin.kamar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_kamar' => 'required|unique:kamar',
            'tipe_kamar' => 'required',
            'harga_permalam' => 'required|numeric',
            'kapasitas' => 'required|integer',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|max:2048',
            'fasilitas' => 'nullable|array',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('kamar', 'public');
        }

        Kamar::create($validated);

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Kamar $kamar)
    {
        return view('admin.kamar.edit', compact('kamar'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $validated = $request->validate([
            'nomor_kamar' => 'required|unique:kamar,nomor_kamar,' . $kamar->id,
            'tipe_kamar' => 'required',
            'harga_permalam' => 'required|numeric',
            'kapasitas' => 'required|integer',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|max:2048',
            'fasilitas' => 'nullable|array',
            'status_kamar' => 'required|in:tersedia,terisi,maintenance',
        ]);

        if ($request->hasFile('foto')) {
            if ($kamar->foto) {
                Storage::disk('public')->delete($kamar->foto);
            }
            $validated['foto'] = $request->file('foto')->store('kamar', 'public');
        }

        $kamar->update($validated);

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar)
    {
        if ($kamar->pemesanan()->where('status_pemesanan', 'aktif')->exists()) {
            return back()->with('error', 'Kamar tidak dapat dihapus karena memiliki booking aktif.');
        }

        if ($kamar->foto) {
            Storage::disk('public')->delete($kamar->foto);
        }

        $kamar->delete();

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
