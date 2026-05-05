<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Biaya;
use Illuminate\Http\Request;

class BiayaController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        $biayas = Biaya::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal', 'desc')
            ->paginate(15);
            
        return view('admin.biaya.index', compact('biayas', 'year', 'month'));
    }

    public function create()
    {
        return view('admin.biaya.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_biaya' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        Biaya::create($request->all());

        return redirect()->route('admin.biaya.index')->with('success', 'Catatan biaya berhasil ditambahkan.');
    }

    public function edit(Biaya $biaya)
    {
        return view('admin.biaya.edit', compact('biaya'));
    }

    public function update(Request $request, Biaya $biaya)
    {
        $request->validate([
            'item_biaya' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'kategori' => 'required|string',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $biaya->update($request->all());

        return redirect()->route('admin.biaya.index')->with('success', 'Catatan biaya berhasil diperbarui.');
    }

    public function destroy(Biaya $biaya)
    {
        $biaya->delete();
        return redirect()->route('admin.biaya.index')->with('success', 'Catatan biaya berhasil dihapus.');
    }
}
