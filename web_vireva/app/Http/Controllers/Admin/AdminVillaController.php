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
            'harga_permalam' => 'required|numeric|min:1000',
            'jumlah_bedroom' => 'required|integer|min:1|max:20',
            'jumlah_bathroom' => 'required|integer|min:1|max:20',
            'luas_bangunan' => 'nullable|integer|min:1|max:5000',
            'kapasitas' => 'required|integer|min:1|max:50',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|array',
            'foto.*' => 'image|max:10240',
            'fasilitas' => 'nullable|array',
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotoPaths[] = $file->store('villas', 'public');
            }
        }
        $validated['foto'] = $fotoPaths;

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
            'harga_permalam' => 'required|numeric|min:1000',
            'jumlah_bedroom' => 'required|integer|min:1|max:20',
            'jumlah_bathroom' => 'required|integer|min:1|max:20',
            'luas_bangunan' => 'nullable|integer|min:1|max:5000',
            'kapasitas' => 'required|integer|min:1|max:50',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|array',
            'foto.*' => 'image|max:10240',
            'fasilitas' => 'nullable|array',
            'status_villa' => 'required|in:tersedia,terisi,maintenance',
        ]);

        // Handle existing photos
        $existingPhotos = $villa->foto ?? [];
        $keepPhotos = $request->input('old_foto', []);
        
        // Delete photos that were removed in the UI
        foreach ($existingPhotos as $photo) {
            if (!in_array($photo, $keepPhotos)) {
                Storage::disk('public')->delete($photo);
            }
        }
        
        $fotoPaths = $keepPhotos;

        // Handle new uploads
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotoPaths[] = $file->store('villas', 'public');
            }
        }
        
        $validated['foto'] = $fotoPaths;

        $villa->update($validated);

        return redirect()->route('admin.villa.index')->with('success', 'Villa berhasil diperbarui.');
    }

    public function destroy(Villa $villa)
    {
        if ($villa->pemesanan()->where('status_pemesanan', 'aktif')->exists()) {
            return back()->with('error', 'Villa tidak dapat dihapus karena memiliki reservasi aktif.');
        }

        if ($villa->foto && is_array($villa->foto)) {
            foreach ($villa->foto as $photo) {
                Storage::disk('public')->delete($photo);
            }
        } elseif ($villa->foto) {
            Storage::disk('public')->delete($villa->foto);
        }

        $villa->delete();

        return redirect()->route('admin.villa.index')->with('success', 'Villa berhasil dihapus.');
    }
}
