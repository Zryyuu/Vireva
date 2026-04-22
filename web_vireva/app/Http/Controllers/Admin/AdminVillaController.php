<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminVillaController extends Controller
{
    public function index()
    {
        $villas = Villa::all();
        return view('admin.villa.index', compact('villas'));
    }

    public function create()
    {
        return view('admin.villa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_villa' => 'required|unique:villas',
            'tipe_villa' => 'required',
            'harga_permalam' => 'required|numeric',
            'jumlah_bedroom' => 'required|integer',
            'jumlah_bathroom' => 'required|integer',
            'luas_bangunan' => 'nullable|integer',
            'kapasitas' => 'required|integer',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|max:2048',
            'fasilitas' => 'nullable|array',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('villas', 'public');
        }

        Villa::create($validated);

        return redirect()->route('admin.villa.index')->with('success', 'Villa berhasil ditambahkan.');
    }

    public function edit(Villa $villa)
    {
        return view('admin.villa.edit', compact('villa'));
    }

    public function update(Request $request, Villa $villa)
    {
        $validated = $request->validate([
            'nama_villa' => 'required|unique:villas,nama_villa,' . $villa->id,
            'tipe_villa' => 'required',
            'harga_permalam' => 'required|numeric',
            'jumlah_bedroom' => 'required|integer',
            'jumlah_bathroom' => 'required|integer',
            'luas_bangunan' => 'nullable|integer',
            'kapasitas' => 'required|integer',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|max:2048',
            'fasilitas' => 'nullable|array',
            'status_villa' => 'required|in:tersedia,terisi,maintenance',
        ]);

        if ($request->hasFile('foto')) {
            if ($villa->foto) {
                Storage::disk('public')->delete($villa->foto);
            }
            $validated['foto'] = $request->file('foto')->store('villas', 'public');
        }

        $villa->update($validated);

        return redirect()->route('admin.villa.index')->with('success', 'Villa berhasil diperbarui.');
    }

    public function destroy(Villa $villa)
    {
        if ($villa->pemesanan()->where('status_pemesanan', 'aktif')->exists()) {
            return back()->with('error', 'Villa tidak dapat dihapus karena memiliki reservasi aktif.');
        }

        if ($villa->foto) {
            Storage::disk('public')->delete($villa->foto);
        }

        $villa->delete();

        return redirect()->route('admin.villa.index')->with('success', 'Villa berhasil dihapus.');
    }
}
